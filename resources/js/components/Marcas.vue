<template>
    <div class="container ">
     <div class="row justify-content-center">
         <div class="col-md-12">
            <!-- Inicio card busca -->
             <div class="card mb-3">
                 <div class="card-header">Busca de Marcas</div>
                 <div class="card-body">
                    <div class="row">
                        <div class="col mb-3">
                            <input-container-component id="inputId" titulo="ID" id-help="idHelp" texto-ajuda="Opcional. informe o ID do registro.">
                            <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" />
                            </input-container-component>
                        </div>
                        <div class="col mb-3">
                            <input-container-component id="inputNome" titulo="Nome" id-help="nomeHelp" texto-ajuda="Opcional. informe o nome da marca.">
                            <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp">
                            </input-container-component>
                        </div>
                    </div>
                 </div>
                 <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                 </div>
             </div>
             <!-- Fim card busca -->

             <!-- Inicio card listagem -->
             <div class="card">
                 <div class="card-header">Relação de marcas</div>
                 <div class="card-body">
                    <table-component />
                 </div>
                 <div class="card-footer">
                    <button type="botton" class="btn btn-primary btn-sm float-end">Adicionar</button>
                 </div>
             </div>
            <!-- Fim card de listagem -->
         </div>
     </div>
    </div>
 </template>
 
 <script>
  
 </script>
 