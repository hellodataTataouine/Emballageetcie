<div id="confirmation-modal" class="modal fade">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ localize('Confirmation d\'affichage') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <div class="display-4 text-danger"> <i data-feather="x-octagon"></i></div>
                <h6 class="my-0">
                        {{ localize('Si vous afficher le produit is sera afficher dans la liste des produits et dans les résultats de recherche') }}
                   
                        {{ localize('Si vous masquer le produit il sera masquer de la liste des produits et des résultats de recherche ?') }}
                </h6>
                <div class="justify-content-center pb-3">
                    <!-- Updated the href attribute to an empty string -->
                    <a href="" id="confirmation-modal-btn" class="btn btn-warning mt-2">{{ localize('Confirmer') }}</a>
                    <button type="button" class="btn btn-secondary mt-2" data-bs-dismiss="modal">{{ localize('Annuler') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
