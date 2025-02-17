<?php if(!class_exists('Rain\Tpl')){exit;}?><script type="text/javascript">
  
    function alertToken(){


      alert("Conforme Resolução SESP Nº 004 de 28/10/2021 Art.7º, Os interessados em participar das turmas de inclusão para Pessoas com Deficiência (PCD) e/ou laudo médico do CREEBA, deverão comparecer pessoalmente (interessado ou representante legal) no Centro Esportivo do Jardim Lavínia, sito à Av. Capitão Casa - 1.500, no horário das 08:30 às 11:30 e das 13:30 às 16:30, nos dias úteis de terça a sexta-feira até o dia 03/12/2021.")
    }

$(document).ready(function(){

    $('#link').on('change', function () {
        var url = $(this).val(); 
        if (url === 'pessoa-create') { 
            window.open(url, '_self');
        }
        return false;
    });
});

</script>


 <div class="container"> <!-- container 1 -->
            <div class="row"> <!-- row 2 -->
              <div class="col-md-8" style="text-align-last: left; background-color: white; margin: 15px 0px 50px 0px;">


<form action="/checkout">

    <?php if( $error != '' ){ ?>
    <div class="alert alert-danger" role="alert">
    <?php echo htmlspecialchars( $error, ENT_COMPAT, 'UTF-8', FALSE ); ?>
    </div>
    <?php } ?>

    <div class="container">
        <?php $counter1=-1;  if( isset($turma) && ( is_array($turma) || $turma instanceof Traversable ) && sizeof($turma) ) foreach( $turma as $key1 => $value1 ){ $counter1++; ?>
        <!--<div class="row alert alert-primary">
            <div class="col-md-12">
                Turma selecionada! Selecione agora, logo abaixo, a pessoa que irá participar desta turma.
            </div>
        </div>
      -->

     <?php if( $error != '' ){ ?>
        <?php if( $value1["idstatustemporada"] == 2 OR $value1["idstatustemporada"] == 3 OR $value1["idstatustemporada"] == 6 ){ ?>
            <div class="alert alert-danger" style="text-align-last: center;">
                <a style="color: darkblue; text-align-last: center;" href="https://www.saobernardo.sp.gov.br/documents/1136654/1245027/Edital+NM/42aa453e-2d70-8651-96e5-41d2d779d24c">Resolução SESP Nº 004 de 28 de outubro de 2021. </a>
             </div>
        <?php } ?>   
    <?php } ?>



         
        <div class="row alert alert-primary"> 
           <!-- 
           <div class="col-md-3">
                <a href="/turma/<?php echo htmlspecialchars( $value1["idturma"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><img width="145" height="145" alt="poster_1_up" class="shop_thumbnail" src="<?php echo htmlspecialchars( $value1["desphoto"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"></a>
            </div>
            -->                      

            <div class="col-md-6">
               TURMA: <span style="color: #cc5d1e;"><?php echo htmlspecialchars( $value1["idturma"], ENT_COMPAT, 'UTF-8', FALSE ); ?> - <?php echo htmlspecialchars( $value1["descativ"], ENT_COMPAT, 'UTF-8', FALSE ); ?> - <?php echo htmlspecialchars( $value1["periodo"], ENT_COMPAT, 'UTF-8', FALSE ); ?> </span> /
               <span class="amount"> 
                  LOCAL: <?php echo htmlspecialchars( $value1["apelidolocal"], ENT_COMPAT, 'UTF-8', FALSE ); ?> - <?php echo htmlspecialchars( $value1["rua"], ENT_COMPAT, 'UTF-8', FALSE ); ?>, Nº <?php echo htmlspecialchars( $value1["numero"], ENT_COMPAT, 'UTF-8', FALSE ); ?> - <?php echo htmlspecialchars( $value1["bairro"], ENT_COMPAT, 'UTF-8', FALSE ); ?> / 
               </span> 
               <span class="amount">
                  HORÁRIO: <?php echo htmlspecialchars( $value1["diasemana"], ENT_COMPAT, 'UTF-8', FALSE ); ?> - <?php echo htmlspecialchars( $value1["horainicio"], ENT_COMPAT, 'UTF-8', FALSE ); ?> às <?php echo htmlspecialchars( $value1["horatermino"], ENT_COMPAT, 'UTF-8', FALSE ); ?> / 
               </span> 
               <span class="amount">
                 <?php if( $value1["fimidade"] == 99 ){ ?> 
               Para nascidos somente até <?php echo htmlspecialchars( $anoAtual - $value1["initidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?> <br>
              <?php }else{ ?>
              Para nascidos somente entre: <?php echo htmlspecialchars( $anoAtual - $value1["fimidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?> à <?php echo htmlspecialchars( $anoAtual - $value1["initidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?><br>
              <?php } ?>
              </span> 
               <span class="amount">
                  PROFESSOR: <?php echo htmlspecialchars( $value1["apelidoperson"], ENT_COMPAT, 'UTF-8', FALSE ); ?>
               </span> 
                <span class="amount">
                   <br> <br><a title="Remove this item" class="remove" href="/cart/<?php echo htmlspecialchars( $value1["idturma"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/remove"> REMOVER <p style="color: green">(Remover esta, selecionar outra turma)</p></a>
               </span>  

               <input type="text" name="idturma" hidden="" value="<?php echo htmlspecialchars( $value1["idturma"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
               <input type="text" name="idtemporada" hidden="" value="<?php echo htmlspecialchars( $value1["idtemporada"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">  
               <input type="text" name="initidade" hidden="" value="<?php echo htmlspecialchars( $value1["initidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
               <input type="text" name="fimidade" hidden="" value="<?php echo htmlspecialchars( $value1["fimidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">  
               <input type="text" name="idlocal" hidden="" value="<?php echo htmlspecialchars( $value1["idlocal"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">                      
               <input type="text" name="apelidolocal" hidden="" value="<?php echo htmlspecialchars( $value1["apelidolocal"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
               <input type="text" name="sexo" hidden="" value="<?php echo htmlspecialchars( $value1["geneativ"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"> 
               <input type="text" name="tipoativ" hidden="" value="<?php echo htmlspecialchars( $value1["tipoativ"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">    
               <input type="text" name="idmodal" hidden="" value="<?php echo htmlspecialchars( $value1["idmodal"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">                   
            </div>

            <div class="col-md-6">
                <div class="">
                    <div class="">

                        <h5>SELECIONE A PESSOA</h5>
                    
                        <div class="">

                            <?php if( $msgError != '' ){ ?>
                            <div class="alert alert-danger alert-dismissible" style="margin:10px">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <p><?php echo htmlspecialchars( $msgError, ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
                            </div>
                            <?php } ?>
                            <?php if( $msgSuccess != '' ){ ?>
                            <div class="alert alert-success alert-dismissible" style="margin:10px">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <p><?php echo htmlspecialchars( $msgSuccess, ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
                            </div>
                            <?php } ?>
                            <!-- form start -->    
                            <!--                       
                            <div class="box-body">                                                               
                                <select class="form-control" name="idpess" id="link"  onchange="changeFunc(value);">
                                    <option selected="selected" value="0">
                                        Selecione uma pessoa
                                    </option>

                                    <?php if( $pessoa ){ ?>
                                    <option value="pessoa-create">
                                        CADASTRAR UMA NOVA PESSOA
                                    </option>
                                    <?php } ?>                                    
                                    <?php $counter2=-1;  if( isset($pessoa) && ( is_array($pessoa) || $pessoa instanceof Traversable ) && sizeof($pessoa) ) foreach( $pessoa as $key2 => $value2 ){ $counter2++; ?>   
                                    <option <?php if( $value2["iduser"] === $user["iduser"] ){ ?><?php } ?> value="<?php echo htmlspecialchars( $value2["idpess"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value2["nomepess"], ENT_COMPAT, 'UTF-8', FALSE ); ?>; <?php echo calcularIdade($value2["dtnasc"]); ?> anos
                                    </option>

                                    <?php }else{ ?>
                                    <option value="0">
                                        Não há pessoas cadastradas
                                    </option>
                                    <option value="pessoa-create">
                                        CADASTRAR UMA NOVA PESSOA
                                    </option>
                                    <?php } ?>
                                </select>
                                 </div>
                                -->

                                <div class="box-body">
                                    <?php $counter2=-1;  if( isset($pessoa) && ( is_array($pessoa) || $pessoa instanceof Traversable ) && sizeof($pessoa) ) foreach( $pessoa as $key2 => $value2 ){ $counter2++; ?>
                                    <p>
                                    <input type="radio" name="idpess" value="<?php echo htmlspecialchars( $value2["idpess"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"> 
                                    <?php echo htmlspecialchars( $value2["nomepess"], ENT_COMPAT, 'UTF-8', FALSE ); ?>; <?php echo calcularIdade($value2["dtnasc"]); ?> anos; 
                                    </p>                                
                                    <?php }else{ ?>
                                    <p>
                                         Não há pessoas inseridas
                                    </p>                                   
                                       
                                    <?php } ?>
                                    
                                    <p>
                                       <a href="/pessoa-create">  INSERIR UMA NOVA PESSOA </a>
                                    </p>
                                </div>
                            
                        </div>
                            <!-- /.box-body -->
                        <div class="box-footer">                                           
                               
                        </div>

                       <?php if( $value1["token"] == 1 ){ ?>
                        <input type="text" name="token" value="" placeholder="Insira aqui o TOKEN"/>&nbsp;
                        <a href="#" onmousemove="alertToken()"><i class="fa fa-info-circle" style="font-size: 24px;"></i></h5></a>
                       <?php } ?>
                           
                       <!-- </form> -->                     

                      
                        <div>&nbsp;</div>
                        <input type="submit" value="Confirmar Inscrição" id="pessoa" class="button alt" formaction="/cart" formmethod="post">
                    </div>
                </div> 
            </div>
             
        </div>
         
        <?php }else{ ?>
        <div class="row">
            <div class="col-md-12 alert alert-info" style="text-align-last: center; font-weight: bold">
                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Clique em uma das opções abaixo para selecionar uma turma</span>                            
            </div>
            
        </div>

  <div class="row" style="">
    <div class="col-md-6 alert" style="text-align-last: center; background-color: #0f71b3; border: solid 5px; border-color: white;  border-radius: 25px;  padding-left: 0px ">
       <a class="btn" href="/locais" style="color: white; font-weight: bold;">Turmas por LOCAL (Crec)
       </a>
    </div>
    <div class="col-md-6 alert" style="text-align-last: center; background-color: #cc5d1e; border: solid 5px; border-color: white; border-radius: 25px;  padding-left: 0px">
       <a class="btn" href="/modalidades" style="color: white; font-weight: bold" >Turmas por MODALIDADE
       </a>
    </div>
    
  </div>
  <?php } ?>
</div>                             
                         
</form>
 </div> <!-- final da index -->


            
                        
                




                                                                
                           