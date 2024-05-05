<template>
        <div class="container py-4 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
                        <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                        <p class="text-white-50 mb-5">Por favor insira seu login e senha!</p>
                            <form method="POST" action="" @submit.prevent="login($event)">
                                <input type="hidden" name="_token" :value="csrf_token"/>
                                <div data-mdb-input-init class="form-outline form-white mb-4">
                                    <input v-model="email" id="id-email" type="email" placeholder="Email" class="form-control form-control-lg"
                                    name="email" value="" required autocomplete="email" autofocus>
                                </div>
                                <div data-mdb-input-init class="form-outline form-white mb-4">
                                    <input v-model="password" id="id-password" type="password" placeholder="Senha" class="form-control form-control-lg"
                                    name="password" required autocomplete="current-password">
                                </div>
                                <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#!">Esqueceu a senha?</a></p>
                                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>
                            </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
</template>

<script>
    export default {
        props : ['csrf_token'],
        data(){
            return {
                email: '',
                password: ''
            }
        },
        methods: {
            login(e){
                let url = "http://localhost:8000/api/login";
                let configuracao = {
                    method: 'post',
                    body: new URLSearchParams({
                        'email': this.email,
                        'password': this.password
                    })
                }
                fetch(url, configuracao)
                    .then(response => response.json())
                    .then(data => {
                        if(data.token){
                            document.cookie = 'token='+data.token+';SameSite=Lax'; 
                        }
                        e.target.submit(); //da continuidade ao envio do form de autenticação por sessão
                    });
            }
        }
    }    
</script>
