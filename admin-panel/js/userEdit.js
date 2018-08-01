/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function newModal(id) {

var newModal = `
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Информация о пользователе</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <form id="formWorkUsers">

                                            <div class="row" style="margin-bottom: 15px;">
                                                <div class="col-lg-5 col-md-5 col-xs-12">Фамилия</div>
                                                <div class="col-lg-7 col-md-7 col-xs-12"><input type="text" id="AddSurname" class="form-control"/></div>
                                            </div>
                                            <div class="row" style="margin-bottom: 15px;">
                                                <div class="col-lg-5 col-md-5 col-xs-12">Имя</div>
                                                <div class="col-lg-7 col-md-7 col-xs-12"><input type="text" id="AddName" class="form-control"/></div>
                                            </div>
                                            <div class="row" style="margin-bottom: 15px;">
                                                <div class="col-lg-5 col-md-5 col-xs-12">e-mail</div>
                                                <div class="col-lg-7 col-md-7 col-xs-12"><input type="text" id="AddEmail" class="form-control"/></div>
                                            </div>
                                            <div class="row" style="margin-bottom: 15px;">
                                                <div class="col-lg-5 col-md-5 col-xs-12">Права</div>
                                                <div class="col-lg-7 col-md-7 col-xs-12"><input type="text" id="AddRole" class="form-control" value="0"></div>
                                            </div>
                                        </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" id="addUserButton" data-id="` + id + `" class="btn btn-primary fromEditUser">Сохранить</button>
            </div>
          </div>
    </div>
</div>
  `;
        $('body').append(newModal);
        $.ajax({
        type: 'POST',
                chashe: false,
                url: 'ajax/users/edit_user.php',
                dataType: "json",
                data: {id: id},
                success: function (html) {
                //(html == "") ? alert("Ссылки в поле CONTRAKT_ID не найдено") : $('#cdog').val(html);
                $('#AddLogin').val(html.login);
                        $('#AddPasswd').val(html.password);
                        $('#AddSurname').val(html.surname);
                        $('#AddName').val(html.name);
                        $('#AddRole').val(html.role);
                }
        });
        }

$('#addUserButton').on('click', function (){
   alert("ok"); 
});