@if($errors->any())

    <div class="alert alert-danger" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        @foreach($errors->all() as $error)
        <i class="fa fa-frown-o me-2" aria-hidden="true"></i>{{ $error }}<br>
        @endforeach
    </div>

    {{-- <div class="alert alert-danger">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        <span class=""><svg xmlns="http://www.w3.org/2000/svg" height="40" width="40" viewBox="0 0 24 24"><path fill="#f07f8f" d="M20.05713,22H3.94287A3.02288,3.02288,0,0,1,1.3252,17.46631L9.38232,3.51123a3.02272,3.02272,0,0,1,5.23536,0L22.6748,17.46631A3.02288,3.02288,0,0,1,20.05713,22Z"/><circle cx="12" cy="17" r="1" fill="#e62a45"/><path fill="#e62a45" d="M12,14a1,1,0,0,1-1-1V9a1,1,0,0,1,2,0v4A1,1,0,0,1,12,14Z"/></svg></span>
        <strong>Whoops!</strong> <span style="font-size:13px ">There were some problems with your input:</span>
        <hr class="message-inner-separator">
        <ul class="" style="list-style: circle; margin-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div> --}}
@endif
