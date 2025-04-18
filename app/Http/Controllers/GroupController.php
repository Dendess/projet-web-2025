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

    public function generate(Request $request) {

        $nb = $request->input('nb');
        $nbg = $request->input('nbg');

//        $request->validate([
//            'nb' => 'required|integer|min:1',
//            'nbg' => 'required',
//        ]);
        $students = User::all();


        $prompt = "Je veux répartir ces eleves dans des groupes de {$nb}. Voici les éleves : {$students}. Une fois le groupe fait, calcule la moyenne (group_average) de chaque groupe d'apres la moyenne (average) de chaque étudiant qu'il contient. Fait en sorte que les group_averages soient le plus proche possible les unes des autres. Si un groupe est incomplet, il ne faut pas rajouter d'éleves inexistants.
         Ne rajoute pas d'explication ou d'auxtres informations.   Répondez uniquement avec un JSON strictement conforme à cette structure :
        {
              \\\"groups\\\": [
                {
                  \\\"number\\\": 1,
                  \\\"group_average\\\": <floating number>,

                  \\\"students\\\": [
                     { \\\"id\\\": <number>, \\\"last_name\\\": \\\"<string>\\\", \\\"first_name\\\": \\\"<string>\\\",\\\"average\\\": <float> }
                  ]
                },
            ...
          ]
        }
        Dans le JSON, remplacez la valeur de \\\"number\\\" de chaque groupe d'apres le nombre d'étudiants qu'il contient. Remplacez ensuite group_average par la moyenne des average de tout les éleves du groupe ";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . config('services.gemini.api_key'), [
            'contents' => [
                [
                    //add prompt in the request
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);
        $groups = Group::all();
        $results = json_decode($response->body());
        $text = $results->candidates[0]->content->parts[0]->text;
        dd($text);

        return view('pages.groups.index', compact('response'), compact('groups'));

    }
}

