<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Inicio card busca -->
                <card-component titulo="Busca de marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component id="inputId" titulo="ID" id-help="idHelp"
                                    texto-ajuda="Opcional. informe o ID do registro.">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" />
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component id="inputNome" titulo="Nome" id-help="nomeHelp"
                                    texto-ajuda="Opcional. informe o nome da marca.">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                    </template>
                </card-component>
                <!-- Fim card busca -->

                <!-- Inicio card listagem -->
                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <table-component />
                    </template>
                    <template v-slot:rodape>
                        <button type="botton" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                            data-bs-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
                <!-- Fim card de listagem -->
            </div>
        </div>

        <!-- Inicio modal  -->
        <modal-component id="modalMarca" titulo="Adicionar Marca">
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="novoNome" id-help="novoNomeHelp"
                        texto-ajuda="Informe o nome da marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp"
                            placeholder="Nome da marca">
                    </input-container-component>
                </div>
                <br />
                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG">
                        <br />
                        <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp"
                            placeholder="Selecione uma imagem">
                    </input-container-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
            </template>
        </modal-component>
        <!-- Fim modal  -->
    </div>
</template>



<script>

</script>
