@extends('layouts.app')
@section('content')
    <div id="container">
        <div id="cadastro">
        <h2 class="mb-4 items-center justify-center text-3xl font-extrabold dark:text-white bg-zinc-800 p-3">Cadastrar<span class="bg-blue-100 text-blue-800 text-base font-semibold me-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ms-2">CONTA</span></h2>
        
            <form id="form-cadastro" action="{{ url('/cadastrar-conta') }}" method="POST">
                @csrf
                <select class="selectpicker" id="jogo-select" name="jogo">
                    <option value="">Selecione um jogo</option>
                    <option value="novo">Novo jogo</option>
                </select>
                <input type="text" id="novo-jogo-input" name="novo_jogo" style="display: none;" placeholder="Nome do novo jogo">
                
                
                <input type="text" id="usuario" name="usuario">

                <label for="senha">Senha:</label>
                <input class="mb-4" type="password" id="senha" name="senha">

                <button class="inline-flex h-12 items-center justify-center gap-2 whitespace-nowrap rounded bg-emerald-500 px-6 text-sm font-medium tracking-wide text-white transition duration-300 hover:bg-emerald-600 focus:bg-emerald-700 focus-visible:outline-none disabled:cursor-not-allowed disabled:border-emerald-300 disabled:bg-emerald-300 disabled:shadow-none" type="submit" id="btn-cadastrar">Cadastrar</button>
            </form>

            <div id="msg-cadastro"></div>
        </div>

        <div id="pesquisa">
        <h2 class="mb-4 items-center justify-center text-3xl font-extrabold dark:text-white bg-zinc-800 p-3">Pesquisar<span class="bg-blue-100 text-blue-800 text-base font-semibold me-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ms-2">CONTAS</span></h2>

            <div class="search-input">
                <form id="form-pesquisa" method="POST">
                    @csrf
                    
                    <input type="text" id="jogoInput" placeholder="Digite o nome do jogo">
                    
                    <div id="suggestions"></div>
                    
                  
                </form>
            </div>

            <div id="msg-pesquisa"></div>
            <div id="resultado"></div>
        </div>
    </div>  
@endsection
