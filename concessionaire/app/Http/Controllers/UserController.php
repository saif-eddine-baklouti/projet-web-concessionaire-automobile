<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ville;
use App\Models\Province;
use App\Models\Privilege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::all();
        return view('user.signup', ["provinces" => $provinces]);
    }

    public function getVilles(Request $request)
    {
        $provinceId = $request->id;
        $villes = Ville::where('ville_province_id', $provinceId)->get();
        return response()->json($villes);
    }


    // Function pour générer les villes dans la bd en englais et en francais
    public function genererVilles (){
        // chemin des villes du fichier json
        $path_villes = resource_path('data/cities.json');
        // lire le contenu json
        $json_read = file_get_contents($path_villes);
        // le transformer en array
        $json_villes = json_decode($json_read, true);
        // rajouter l'id des provinces dans leur villes respectives
        $array_ids =[['BC', 2], ['SK', 10], ['AB', 1], ['QC', 9], ['ON', 7], ['NT', 12], ['NS', 6], ['NL', 5], ['NT', 11], ['PE', 8], ['NB', 4], ['MB', 3], ['YT', 13]];
        foreach ($json_villes as $ville => $value) {

            foreach ($array_ids as $key2 => $pair_id) {

                if($value[1] == $pair_id[0]){     
                    array_unshift($value, $pair_id[1]);
                    $new_villes_array[] = $value;
                }
            }
           
        }
        // Créer les villes dans la bd
        foreach ($new_villes_array as $ville => $value) {
            $nouvelleVille = Ville::create([
                    'ville_en' => $value[1],
                    'ville_fr' => $value[1],
                    'ville_province_id' => $value[0]
                ]);
        }

    }

    // Function pour générer les provinces dans la bd en englais et en francais
    public function insererProvinces(){
        $provinces_en_fr = [
            ['Alberta', 'Alberta'], ['British Columbia', 'Colombie-Britannique'], ['Manitoba','Manitoba'], ['New Brunswick', 'Nouveau-Brunswick'], ['Newfoundland and Labrador', 'Terre-Neuve-et-Labrador'], ['Nova Scotia', 'Nouvelle-Écosse'], ['Ontario','Ontario'], ['Prince Edward Island', 'Île-du-Prince-Édouard'], ['Quebec','Quebec'], ['Saskatchewan','Saskatchewan'], ['Northwest Territories','Territoires du Nord-Ouest'], ['Nunavut', 'Nunavut'], ['Yukon','Yukon']
        ];

        foreach ($provinces_en_fr as $prov => $value) {
            # code...
            $province = Province::create([
                'province_en' => $value[0],
                'province_fr' => $value[1]
            ]);
        };
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'prenom' => 'required|max:50',
            'email' => 'required|email|unique:users',
            'anniversaire' => 'required|date',
            'adresse' => 'required|string',
            'code_postal' => 'required|string',
            'province' => 'numeric',
            'ville' => 'numeric',
            'telephone' => 'required|numeric',
            'telephone_portable' => 'numeric',
            'password' => 'min:6|max:20' 
        ]);

        $password = Hash::make($request->password);

        // je crée privilege en premier pour pouvoir utiliser l'id du privilege dans user 
        $privilege = Privilege::create([
            'pri_role_en' => 'client',
            'pri_role_fr' => 'client'
        ]);
        $user_client = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => $password,
            'anniversaire' => $request->anniversaire,
            'adresse' => $request->adresse,
            'code_postal' => $request->code_postal,
            'province_id' => $request->province,
            'ville_id' => $request->ville,
            'telephone' => $request->telephone,
            'telephone_portable' => $request->telephone_portable,
            'privilege_id' => $privilege->id,
        ]);
        
        // return $user->type;
        // if($user->type == "etudiant"){
            // return redirect(route('/'));
            // return redirect(route('user.login'))->withSuccess('Utilisateur enregistré comme etudiant');
        // }else{
            // return redirect(route('user.index'))->withSuccess('User created successfully!');
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
    public function forgot(){
        return view('user.forgot');
    }
}