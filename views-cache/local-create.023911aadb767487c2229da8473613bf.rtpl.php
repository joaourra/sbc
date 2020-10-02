<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Lista de Crec´s
  </h1>
  <ol class="breadcrumb">
    <li><a href="/professor"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/professor/local">Crec´s</a></li>
    <!--<li class="active"><a href="/professor/local/create">Cadastrar</a></li>-->
  </ol>
</section>

<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Nova Crec</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" action="/professor/local/create" method="post">
          <div class="box-body">
            <div class="form-group">
              <label for="apelidolocal">Apelido do Crec</label>
              <input type="text" class="form-control" id="apelidolocal" name="apelidolocal" placeholder="Informe o apelido do Crec">
            </div>
            <div class="form-group">
              <label for="nomelocal">Nome do Crec</label>
              <input type="text" class="form-control" id="nomelocal" name="nomelocal" placeholder="Informe o nome oficial do Crec">
            </div>
            <div class="form-group">
              <label for="cep">Cep</label>
              <input type="text" class="form-control" id="cep" name="cep" placeholder="Informe o Cep de onde está localizado o Crec">
            </div>
            <div class="form-group">
              <label for="rua">Rua</label>
              <input type="text" class="form-control" id="rua" name="rua" placeholder="Informe o nome da rua ou avenida">
            </div>
            <div class="form-group">
              <label for="numero">Número</label>
              <input type="text" class="form-control" id="numero" name="numero" placeholder="Informe o número">
            </div>
            <div class="form-group">
              <label for="complemento">Complemento</label>
              <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Informe o complemento, se houver">
            </div>
            <div class="form-group">
              <label for="bairro">Bairro</label>
              <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Informe o nome do Bairro">
            </div>
            <div class="form-group">
              <label for="cidade">Cidade</label>
              <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Informe o nome da cidade">
            </div>
            <div class="form-group">
              <label for="estado">Estado</label>
              <input type="text" class="form-control" id="estado" name="estado" placeholder="Informe o nome do Estado">
            </div>
            <div class="form-group">
              <label for="telefone">Telefone</label>
              <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Informe o númeor do telefone do local">
            </div>           
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-success">Cadastrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->