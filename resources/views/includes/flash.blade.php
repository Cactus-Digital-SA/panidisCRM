@if(session('success'))
    <script type="module">
        Swal.fire({
            title: '{{__('locale.Update')}}!',
            html: '{!! session('success') !!}',
            icon: 'success',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        })
    </script>
@endif
@if(session('danger') || session('error'))
    <script type="module">
        Swal.fire({
            title: '{{__('locale.Update')}}!',
            html: '{!! session('danger') !!} {!! session('error') !!}',
            icon: 'error',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        })
    </script>
@endif
@if(session('warning'))
    <script type="module">
        Swal.fire({
            title: '{{__('locale.Update')}}!',
            html: '{!! session('warning') !!}',
            icon: 'warning',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        })
    </script>
@endif
@if ($errors->any())
    <script type="module">
        var html_errors="";
        @foreach($errors->all() as $error)
            html_errors += "<p>{{ $error }}</p>";
        @endforeach
        Swal.fire({
            title: '{{__('locale.Update')}}!',
            html: html_errors,
            icon: 'error',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        })
    </script>
@endif
<script type="module">
    window.addEventListener('swal',function(e){
        Swal.fire({
            'title' :  e.detail.title,
            'icon' :  e.detail.icon,
            'timer': 3000,
            'toast' :true,
            'timerProgressBar':true,
            'position' : 'top-right'
        });
    });
</script>


@if(session('info'))
    <script type="module">
        $(document).ready(function () {
            toastr.info('{!! session('info') !!}');
        });
    </script>
@elseif(session('status'))
    <script type="module">
        $(document).ready(function () {
            toastr.success('{!! session('status') !!}');
        });
    </script>
@endif


