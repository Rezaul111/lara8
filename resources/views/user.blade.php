<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
    <link href="{{asset('/')}}bs4-css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <title>Ajax Crud</title>

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-9 mx-auto py-4">
            <div class="card">
                <div class="card-header">
                    <a>All Users </a>
                    <a class="float-right btn btn-outline-success btn-sm add-user"><i class="fa fa-plus-circle mr-1"></i>Add</a>
                </div>
                <div class="card-body">
                    <h5 class="msg text-danger text-center"></h5>
                 <table class="table table-bordered">
                     <thead>
                     <tr>
                         <td>#</td>
                         <td>Name</td>
                         <td>Email</td>
                         <td>Mobile</td>
                         <td>Image</td>
                         <td>Action</td>
                     </tr>
                     </thead>
                     <tbody class="allUsersInfo"></tbody>
                 </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5> User Info</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data" class="addForm">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name"  class="form-control user-name" placeholder="Enter User Name"/>
                            <input type="hidden" name="id"  class="form-control user-id" value=""/>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email"  class="form-control user-email" placeholder="Enter User Email"/>
                        </div>
                        <div class="form-group">
                            <input type="number" name="mobile"  class="form-control user-mobile" placeholder="Enter User mobile"/>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image"  class="form-control-file" accept="image/*" placeholder="Enter User mobile"/>
                            <img src="" class="user-image" height="50" width="50"/>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-outline-info">SAVE or UPDATE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('/')}}bs4-js/jquery-3.6.0.min.js"></script>
<script src="{{asset('/')}}bs4-js/bootstrap.bundle.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    $(document).ready(function (){
        getAllUsers();
    });
</script>
<script>
    $('.add-user').click(function () {
        $('#myModal').modal('show');
        $('.addForm').submit(function (e){
            e.preventDefault();
            $.ajax({
                url         : 'new-user',
                method      : 'POST',
                data        : new FormData(this),
                dataType    : 'JSON',
                contentType : false,
                processData : false,
                success     : function (data) {
                    console.log(data)
                    $('.msg').html(data);
                    $('#myModal').modal('hide');
                    getAllUsers();
                    $('.addForm').trigger('reset');

                }
            });
        });
    })
    function getAllUsers(){
        $.ajax({
            url     : 'get-all-users',
            method  : 'GET',
            dataType  : 'JSON',
            success:function (data){
               var res = $('.allUsersInfo');
               res.empty();
               var index = 1;
               var tr = '';
               $.each(data,function (key,val){
                    tr += '<tr>';
                    tr+='<td>'+ index++ +'</td>'
                    tr+='<td>'+val.name+'</td>'
                    tr+='<td>'+val.email+'</td>'
                    tr+='<td>'+val.mobile+'</td>'
                    tr+='<td><img src=" '+ val.image+' " height="50" width="50"/></td>'
                   tr+='<td>'
                   tr+='<a class="btn btn-outline-info btn-sm edit-user" data-id="'+val.id+'"><i class="fa fa-edit"></i></a>'
                   tr+='<a class="btn btn-outline-danger btn-sm delete-user" data-id="'+val.id+'"><i class="fa fa-trash"></i></a>'
                   tr+='</td>'
                    tr += '</tr>';
               });
               res.append(tr);
            }
        });
    }
    $('.allUsersInfo').on('click','.edit-user',function (){
        $('#myModal').modal('show');
        var id = $(this).attr('data-id');
       $.ajax({
           url : 'edit-user/'+id,
           method:'GET',
           dataType:'JSON',
           success:function (data){
               console.log(data)
               $('.user-id').val(data.id);
               $('.user-name').val(data.name);
               $('.user-email').val(data.email);
               $('.user-mobile').val(data.mobile);
               $('.user-image').attr('src',data.image);
           }
       });
    });

    $('.addForm').submit(function (e){
        e.preventDefault();
        $.ajax({
            url         : 'update-user',
            method      : 'POST',
            data        : new FormData(this),
            dataType    : 'JSON',
            contentType : false,
            processData : false,
            success     : function (data) {
                console.log(data)
                $('.msg').html(data);
                getAllUsers();
                $('#myModal').modal('hide');
            }
        });
    })
    $('.allUsersInfo').on('click','.delete-user',function (){
        var ck = confirm('Are you sure want to delete ??');
        if(ck == true){
            var id = $(this).attr('data-id');
            $.ajax({
                url : 'delete-user/'+id,
                method:'GET',
                dataType:'JSON',
                success:function (data){
                    console.log(data);
                    $('.msg').html(data);
                    getAllUsers();
                }
            });
        }

    });

</script>

</body>
</html>
