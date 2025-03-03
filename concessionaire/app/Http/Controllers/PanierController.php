<?php

namespace App\Http\Controllers;

use App\Models\Taxe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class PanierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tps = 0;
        $tpsPanier = 0;
        $tvp = 0;
        $tvpPanier = 0;
        $total = 0;
        $totalTaxeInclue = 0;

        
        $tauxTaxe = Taxe::where('taxe_province_id', Auth::user()->province_id)->get(); 
        
        foreach ($tauxTaxe as $taxe) {
            if( $taxe['taxe_nom'] == 'TPS' ) {
                $tps = $taxe['taxe_rate'];
            } elseif ($taxe['taxe_nom'] == 'TVP' || $taxe['taxe_nom'] == 'TVQ' ) {
                $tvp = $taxe['taxe_rate'];
            };
        }
        // return $tauxTaxe;

        if (Session::has('panier')) { 
        $panier = Session::get('panier');
        // return $panier;
        
        // calcul prix totale
        foreach ($panier as $objVoiture) {
            $total = $total + $objVoiture['prix'];
        }
        // calcule tps
        $tpsPanier = $total * $tps;
        // return $tpsPanier;    
        
        // calcule tvp
        $tvpPanier = $total * $tvp;
        
        // calcule totale 
        $totalTaxeInclue = $total + $tpsPanier + $tvpPanier;
        
        // return $totalTaxeInclue;
        
        
        return view('panier.index', ['panier' => $panier, 'total' => round($total, 2), 'tps' => round($tpsPanier, 2), 'tvp' => round($tvpPanier, 2), 'totalTaxeInclue' => round($totalTaxeInclue, 2) ]);
    } else {
        
        $panier = [];
        return view('panier.index', ['panier' => $panier, 'total' => round($total, 2), 'tps' => round($tpsPanier, 2), 'tvp' => round($tvpPanier, 2), 'totalTaxeInclue' => round($totalTaxeInclue, 2) ]);
    }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $tauxTaxe = Taxe::where('taxe_province_id', Auth::user()->province_id)->get(); 
        
        foreach ($tauxTaxe as $taxe) {
            if( $taxe['taxe_nom'] == 'TPS' ) {
                $tps = $taxe['taxe_rate'];
            } elseif ($taxe['taxe_nom'] == 'TVP' || $taxe['taxe_nom'] == 'TVQ' ) {
                $tvp = $taxe['taxe_rate'];
            };
        }

        
        $taxeAjouter = ($request->prix * $tps) + ($request->prix * $tvp);
        $prixTaxeInclue = $request->prix + $taxeAjouter;
        
        // return $request->prix;

        if (Session::has('panier')) { 
            $panier = Session::get('panier');
            
            foreach ($panier as $index => $objVoiture) {
                if ($request->voiture_id == $objVoiture['voiture_id'] && $request->user_id == $objVoiture['user_id'] ) {
                    unset($panier[$index]);
                    Session::put('panier', $panier);

                    return  redirect()->route('panier.index')->with('success', __('lang.controllers.removed_from_cart'));
                    
                }
            }

            $nouveauArticle = [ 
                'voiture_id' => $request->voiture_id,
                'photo_principale' => $request->photo_principale, 
                'marque' => $request->marque,
                'modele' => $request->modele, 
                'prix' => round($request->prix, 2), 
                'prixTaxeInclue' => round($prixTaxeInclue, 2), 
                'province_user_id' => $request->province_user_id,
                'user_id' => $request->user_id
            ];
            $panier[] = $nouveauArticle;
            
            Session::put('panier', $panier);
            
            
        } else {
            $panier[] = [ 
    
                'voiture_id' => $request->voiture_id, 
                'photo_principale' => $request->photo_principale,
                'marque' => $request->marque,
                'modele' => $request->modele, 
                'prix' => round($request->prix, 2), 
                'prixTaxeInclue' => round($prixTaxeInclue, 2), 
                'province_user_id' => $request->province_user_id,
                'user_id' => $request->user_id
            ];
            Session::put('panier', $panier );
        }

        return  redirect()->route('panier.index')->with('success', __('lang.controllers.added_to_cart'));        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        // return $request->voiture_id;
    
        if (Session::has('panier')) { 
            $panier = Session::get('panier');
            
            foreach ($panier as $index => $objVoiture) {
                if ($request->voiture_id == $objVoiture['voiture_id'] ) {
                    unset($panier[$index]);
                    Session::put('panier', $panier);

                    return  redirect()->route('panier.index')->with('success', __('lang.controllers.removed_from_cart'));
                    
                }
            }

        }
    }
}
