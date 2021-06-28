<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Nova Temporada
  </h1>
  <ol class="breadcrumb">
    <li><a href="/professor"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/professor/temporada">Nova temporada</a></li>
    <!--<li class="active"><a href="/professor/espaco/create">Cadastrar</a></li>-->
  </ol>
</section>

<!-- Main content -->

  <div class="row">
  	<div class="col-md-12">
  		<div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"> Nova Temporada</h3>
        </div>
        <?php if( $error != '' ){ ?>

        <div class="alert alert-danger" style="margin: 0px 10px 0px 10px">
        <?php echo htmlspecialchars( $error, ENT_COMPAT, 'UTF-8', FALSE ); ?>

        </div>
        <?php } ?>

        <!-- /.box-header -->
        <!-- form start --><form role="form" action="/professor/temporada/create" method="post">
          
            <div class="box-header">
              <label for="desctemporada"> Descrição da temporada</label>
              <input type="text" class="form-control" id="desctemporada" name="desctemporada" placeholder="Descreva temporada">
            </div>
            <div class="box-header">
              <label for="dtinicinscricao"> Data de Início das Inscrições</label>
              <input type="date" class="form-control" id="dtinicinscricao" name="dtinicinscricao">
            </div>
            <div class="box-header">
              <label for="dtterminscricao"> Data do Fim das Inscrições</label>
              <input type="date" class="form-control" id="dtterminscricao" name="dtterminscricao">
            </div>
            <div class="box-header">
              <label for="dtinicmatricula"> Data de Início das Matrículas</label>
              <input type="date" class="form-control" id="dtinicmatricula" name="dtinicmatricula">
            </div>
            <div class="box-header">
              <label for="dttermmatricula"> Data do Fim das Matrículas</label>
              <input type="date" class="form-control" id="dttermmatricula" name="dttermmatricula">
            </div>            
            <div class="box-header">
              <label for="idstatustemporada"> Status Temporada</label>
              <select class="form-control" name="idstatustemporada">
              <option selected="selected">Selecione o Status da Temporada</option>                          
                <?php $counter1=-1;  if( isset($statustemporada) && ( is_array($statustemporada) || $statustemporada instanceof Traversable ) && sizeof($statustemporada) ) foreach( $statustemporada as $key1 => $value1 ){ $counter1++; ?>

                <option value="<?php echo htmlspecialchars( $value1["idstatustemporada"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["idstatustemporada"], ENT_COMPAT, 'UTF-8', FALSE ); ?> - <?php echo htmlspecialchars( $value1["descstatustemporada"], ENT_COMPAT, 'UTF-8', FALSE ); ?> </option>
                <?php } ?>

              </select>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-success">Cadastrar</button>
            &nbsp&nbsp&nbsp&nbsp
            <a type="button" class="btn btn-danger" href="/professor/temporada">Cancelar</a>
          </div>
        </form>
      </div>
  	</div>
  </div>

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->