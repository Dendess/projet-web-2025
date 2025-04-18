<?php

    namespace App\Http\Controllers;

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

            $groups = Group::all();
            return view('pages.groups.index', compact('groups'));
        }

        public function generate(Request $request)
        {

            $nb = $request->input('nb');
            $nbp = $request->input('nbp');

            $students = User::where('cohort_id', $nbp)->get()   ;
            $previousgroups = Group::all();

            // Convertir les étudiants en JSON lisible dans le prompt
            $studentsArray = $students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'average' => $student->average,
                ];
            });

            $studentsJson = json_encode($studentsArray, JSON_PRETTY_PRINT);

            $prompt = <<<EOT
    Je veux répartir ces élèves dans des groupes de {$nb}. Voici les élèves : {$studentsJson}.Voici les groupes déja fait :{$previousgroups}. Evite au maximum les répétitions. Une fois les groupes faits, calcule la moyenne (group_average) de chaque groupe d'après la moyenne (average) de chaque étudiant qu'il contient. Fait en sorte que les group_averages soient le plus proche possible les unes des autres. Si un groupe est incomplet, ne rajoute pas d'élèves inexistants.
    Répond uniquement avec un JSON strictement conforme à cette structure :
    {
      "groups": [
        {
          "number": 1,
          "group_average": <float>,
          "students": [
            { "id": <int>, "last_name": "<string>", "first_name": "<string>", "average": <float> }
          ]
        },
        ...
      ]
    }
    La moyenne de chaque groupe (group_average) doit être un nombre flottant avec 2 décimales de précision.
    EOT;

            // Appel à l'API Gemini
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

            $results = json_decode($response->body());
            $text = $results->candidates[0]->content->parts[0]->text ?? null;

            // Nettoyage des balises Markdown (```json ... ```)
            $cleanText = preg_replace('/^```json|```$/', '', trim($text));

            // Décodage
            $data = json_decode($cleanText, true);

            if (!$data || !isset($data['groups'])) {
                return back()->withErrors(['format' => 'Le JSON retourné est invalide ou mal formé.']);
            }

            foreach ($data['groups'] as $groupData) {
                $group = Group::create([
                    'size' => count($groupData['students']),
                    'group_average' => round($groupData['group_average'], 2),
                ]);

                foreach ($groupData['students'] as $student) {
                    $group->users()->attach($student['id']);
                }
            }
            $groups = Group::with('users')->get(); // Récupérer les groupes avec les étudiants pour l'affichage
            return redirect()->route('groups.index')->with('success', 'Groupes créés avec succès.');        }
        public function show($id)
        {
            $group = Group::with('users')->findOrFail($id);
            return view('pages.groups.show', compact('group'));
        }
    }

