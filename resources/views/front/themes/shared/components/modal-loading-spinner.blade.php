{{-- LOADING SPINNER MODAL START --}}
<div class="modal fade" id="loadingSpinnerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered d-flex justify-content-center" role="document">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>
<script>
    function spinnerModalShow(){
        $('#loadingSpinnerModal').modal('show');    
    }
    function spinnerModalHide(){
        setTimeout(function() { 
            $('#loadingSpinnerModal').modal('hide');            
        }, 500);
    }
</script>
{{-- LOADING SPINNER MODAL ENDS --}}