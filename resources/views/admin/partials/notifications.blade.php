<script>
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    timeOut: 10000,
};
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    @if(session('success'))
        toastr.success(@json(session('success')));
    @endif

    @if(session('error'))
        toastr.error(@json(session('error')));
    @endif

    @if(session('warning'))
        toastr.warning(@json(session('warning')));
    @endif

    @if(session('info'))
        toastr.info(@json(session('info')));
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error(@json($error));
        @endforeach
    @endif

});
</script>