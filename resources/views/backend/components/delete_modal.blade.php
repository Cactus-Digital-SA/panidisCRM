<!-- Διαγραφή -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5">
                <form id="deleteForm" method="POST" class="row gy-1 pt-75" enctype="multipart/form-data" >
                    @method('DELETE')
                    @csrf()
                    <div class="col-12">
                        <div style="text-align: center;"> <h3 style="color: #EA5455">Είστε σίγουροι οτι θέλετε να γίνει η Διαγραφή;</h3></div>
                        <br>
                    </div>
                    <div class="col-12 text-center mt-2">
                        <button type="submit" class="btn btn-primary ">Διαγραφή <i class="ti ti-trash ti-xs ms-1"></i></button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                            Άκυρο
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Διαγραφή -->

