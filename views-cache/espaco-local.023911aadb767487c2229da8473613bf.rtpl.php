<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Escolha os espaços para <?php echo htmlspecialchars( $local["apelidolocal"], ENT_COMPAT, 'UTF-8', FALSE ); ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="/professor"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/professor/local">Espaço</a></li>
    <li><a href="/professor/local/<?php echo htmlspecialchars( $local["idlocal"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $local["apelidolocal"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a></li>
    <li class="active"><a href="/professor/local/<?php echo htmlspecialchars( $local["idlocal"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/espaco">Espaço</a></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                <h3 class="box-title">Todos os Espaços</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th style="width: 10px">#</th>
                            <th>Espaço</th>
                            <th style="width: 240px">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $counter1=-1;  if( isset($espacoNotRelated) && ( is_array($espacoNotRelated) || $espacoNotRelated instanceof Traversable ) && sizeof($espacoNotRelated) ) foreach( $espacoNotRelated as $key1 => $value1 ){ $counter1++; ?>
                            <tr>
                            <td></td>
                            <td><?php echo htmlspecialchars( $value1["idespaco"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                            <td>
                                <a href="/professor/local/<?php echo htmlspecialchars( $local["idlocal"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/espaco/<?php echo htmlspecialchars( $value1["idespaco"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-arrow-right"></i> Adicionar</a>
                            </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                <h3 class="box-title">Espaço para <?php echo htmlspecialchars( $local["apelidolocal"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th style="width: 10px">#</th>
                            <th>Espaços</th>
                            <th style="width: 240px">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $counter1=-1;  if( isset($espacoRelated) && ( is_array($espacoRelated) || $espacoRelated instanceof Traversable ) && sizeof($espacoRelated) ) foreach( $espacoRelated as $key1 => $value1 ){ $counter1++; ?>
                            <tr>
                            <td><?php echo htmlspecialchars( $value1["idespaco"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                            <td>
                                <a href="/professor/local/<?php echo htmlspecialchars( $local["idlocal"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/espaco/<?php echo htmlspecialchars( $value1["idespaco"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/remove" class="btn btn-primary btn-xs pull-right"><i class="fa fa-arrow-left"></i> Remover</a>
                            </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->