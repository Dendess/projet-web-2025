<?php

    namespace App\Http\Controllers;

    use App\Models\Cohort;
    use App\Models\Group;
    use App\Models\User;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Contracts\View\View;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Http;

    class GroupController extends Controller
    {
        /**
         * Display the page
         *
         * @return Factory|View|Application|object
         */
        public function index() {
            // Récupère toutes les promotions
            $cohortsall = Cohort::all();

            // Récupère tous les groupes existants
            $groups = Group::all();

            // Retourne la vue 'pages.groups.index' avec les données des groupes et des promotions
            return view('pages.groups.index', compact('groups','cohortsall'));
        }

        public function generate(Request $request)
        {
            // Récupère la taille des groupes et l'ID de la cohorte depuis la requête
            $nb = $request->input('nb');
            $nbp = $request->input('nbp');

            // Ajuste l'ID de la promotion (probablement pour un décalage d'index)
            $nbp = $nbp - 1;

            // Récupère tous les étudiants dans la promotion spécifiée
            $students = User::where('cohort_id', $nbp)->get();

            // Récupère tous les groupes existants pour éviter les répétitions
            $previousgroups = Group::all();

            // Crée un tableau lisible des étudiants en format JSON
            $studentsArray = $students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'average' => $student->average,
                ];
            });

            // Encode les étudiants en JSON formaté
            $studentsJson = json_encode($studentsArray, JSON_PRETTY_PRINT);

            // Crée un prompt pour l'API Gemini pour générer la répartition des groupes
            $prompt = <<<EOT
Je veux répartir ces élèves dans des groupes de {$nb}. Voici les élèves : {$studentsJson}. Voici les groupes déjà faits : {$previousgroups}. Si un groupe est incomplet, ne rajoute pas d'élèves inexistants. Voici tes conditions, ignore-les uniquement si tu n'as pas le choix. Evite au maximum les répétitions. Une fois les groupes faits, calcule la moyenne (group_average) de chaque groupe d'après la moyenne (average) de chaque étudiant qu'il contient. Fait en sorte que les group_averages soient le plus proche possible les unes des autres.
Répond uniquement avec un JSON strictement conforme à cette structure :
{
  "groups": [
    {
      "number": 1,
      "group_average": <float>,
      "cohort_id": {$nbp},
      "students": [
        { "id": <int>, "last_name": "<string>", "first_name": "<string>", "average": <float> }
      ]
    },
    ...
  ]
}
La moyenne de chaque groupe (group_average) doit être un nombre flottant avec 2 décimales de précision.
EOT;

            // Envoie la requête à l'API Gemini avec les données du prompt
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . config('services.gemini.api_key'), [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            // Récupère le contenu de la réponse de l'API
            $results = json_decode($response->body());
            $text = $results->candidates[0]->content->parts[0]->text ?? null;

            // Nettoie les balises Markdown dans le texte retourné
            $cleanText = preg_replace('/^```json|```$/', '', trim($text));

            // Décodage du JSON nettoyé
            $data = json_decode($cleanText, true);

            // Si le format retourné n'est pas valide, renvoie une erreur
            if (!$data || !isset($data['groups'])) {
                return back()->withErrors(['format' => 'Le JSON retourné est invalide ou mal formé.']);
            }

            // Crée les groupes à partir des données retournées par l'API
            foreach ($data['groups'] as $groupData) {
                $group = Group::create([
                    'size' => count($groupData['students']),
                    'group_average' => round($groupData['group_average'], 2),
                    'cohort_id' => round($groupData['cohort_id'], 2),
                ]);

                // Associe les étudiants au groupe créé
                foreach ($groupData['students'] as $student) {
                    $group->users()->attach($student['id']);
                }
            }

            // Récupère les promotions et groupes mis à jour
            $cohortsall = Cohort::all();
            $groups = Group::all();

            // Redirige vers la page des groupes avec un message de succès
            return redirect()->route('groups.index')->with('success', 'Groupes créés avec succès.');
        }

        public function show($id)
        {
            // Récupère le groupe avec les étudiants associés
            $group = Group::with('users')->findOrFail($id);

            // Retourne la vue 'pages.groups.show' avec les données du groupe
            return view('pages.groups.show', compact('group'));
        }
    }
