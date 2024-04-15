@extends('layouts.app')
@section('title', 'Inscription')
@section('content')
<!-- gestion des erreur -->
    @if(!$errors->isEmpty())
    <div class="container">

        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    <div class="row justify-content-center mt-5 mb-5 text-center">
        <form action="{{ route('user.store') }}" class="form-signin col-sm-4 mb-3" method="POST">
            @csrf
            <img class="mb-4" src="{{asset('img/logo.png')}}" alt="" width="176" height="155">
            <h1 class="h3 mb-3 font-weight-normal">Créez votre compte</h1>
            <!-- name -->
            <div class="form-group mb-3 text-start">
                <label for="inputNom" class="sr-only form-label">Nom</label>
                <input name="name" type="text" id="inputNom" class="form-control" placeholder="Nom*" required autofocus>
            </div>
            <!-- prenom -->
            <div class="form-group mb-3 text-start">
                <label for="inputPrenom" class="sr-only form-label">Prénom</label>
                <input name="prenom" type="text" id="inputPrenom" class="form-control" placeholder="Prenom*" required autofocus>
            </div>
            <!-- anniversaire -->
            <div class="form-group mb-3 text-start">
                <label for="inputAnniversaire" class="sr-only form-label">Anniversaire</label>
                <input name="anniversaire" type="date" id="inputAnniversaire" class="form-control" placeholder="Anniversaire*" required autofocus>
            </div>
            <!-- adresse -->
            <div class="form-group mb-3 text-start">
                <label for="inputAdresse" class="sr-only form-label">Adresse</label>
                <input name="adresse" type="text" id="inputAdresse" class="form-control" placeholder="Adresse*" required autofocus>
            </div>
            <!-- code postal -->
            <div class="form-group mb-3 text-start">
                <label for="inputCode_postal" class="sr-only form-label">Code Postal</label>
                <input name="code_postal" type="text" id="inputCode_postal" class="form-control" placeholder="Code Postal*" required autofocus>
            </div>
            <!-- province -->
            <div class="form-group mb-3 text-start">
                <label for="inputProvince" class="form-label">Province</label>
                <select name="province" id="inputProvince" class="form-control">
                        <option value="" >Choisir la province</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" >{{ $province->province_en }}</option>
                        @endforeach
                </select>
            </div>
            <!-- ville -->
            <div class="form-group mb-3 text-start">
                <label for="inputVille" class="form-label">Ville</label>
                <select name="ville" id="inputVille" class="form-control" disabled>
                        <!-- <option value="" >Choisir ville</option> -->
                </select>
            </div>
            <!-- telephone -->
            <div class="d-flex  gap-4">
                <div class="form-group mb-3 text-start w-50">
                    <label for="inputTelephone" class="sr-only form-label">Telephone</label>
                    <input name="telephone" type="tel" id="inputTelephone" class="form-control" placeholder="Téléphone" required autofocus>
                </div>
                <!-- telephone_portable -->
                <div class="form-group mb-3 text-start w-50">
                    <label for="inputTelephone_portable" class="sr-only form-label">Telephone Portable</label>
                    <input name="telephone_portable" type="tel" id="inputTelephone_portable" class="form-control" placeholder="Téléphone Portable" required autofocus>
                </div>
            </div>
            <!-- courriel -->
            <div class="form-group mb-3 text-start">
                <label for="inputEmail" class="sr-only form-label">Courriel</label>
                <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Courriel*" required autofocus>
            </div>
            <!-- mot de passe -->
            <div class="form-group mb-3 text-start">
                <label for="inputPassword" class="sr-only form-label">Mot de passe</label>
                <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Mot de passe*" required>
            </div>
            <button class="btn btn-lg btn-primary w-100" type="submit">S'inscrire</button>
        </form>
        <p>Vous avez déjà un compte ? <a href="#" class="link-underline-primary">Se connecter</a></p>
    </div>

    <!-- Script pour generer les villes a partir de la province selectionnée -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#inputProvince').change(function() {
                var provinceId = $(this).val();
                if (provinceId) {
                    $.ajax({
                        type: "GET",
                        url: "/villes/"+provinceId,
                        // data: { ville_province_id: provinceId },
                        success: function(villes) {
                            console.log(villes);
                            $('#inputVille').empty();
                            $.each(villes, function(key, value) {
                                $('#inputVille').append('<option value="' + value.id + '">' + value.ville_en + '</option>');
                            });
                            $('#inputVille').prop('disabled', false);
                        }
                    });
                } else {
                    $('#inputVille').empty();
                    $('#inputVille').prop('disabled', true);
                }
            });
        });
    </script>
@endsection