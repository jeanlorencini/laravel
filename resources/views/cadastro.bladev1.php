
<!-- resources/views/index.blade.php -->
@extends('layouts.app')

@section('content')

<h1>Sistema de Contas de Jogos</h1>  

<div id="container">

    <div id="cadastro">

      <h2>Cadastrar Conta de Jogo</h2>

      <form id="form-cadastro" action="{{ url('/cadastrar-conta') }}" method="POST">
    @csrf

        <select id="jogo-select">
          <option value="">Selecione um jogo</option>
          <option value="novo">Novo jogo</option>
        </select>

        <label for="jogo">Jogo:</label>
        <input type="text" id="jogo" name="jogo"> 
        
        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario">

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha">

        <button type="submit" id="btn-cadastrar">Cadastrar</button>
      
      </form>

      <div id="msg-cadastro"></div>

    </div>

    <div id="pesquisa">

      <h2>Pesquisar Contas de Jogos</h2>

      <div class="search-input">
        
      <form id="form-pesquisa" method="POST">
  @csrf
        
          <input type="text" id="jogoInput">
        
          <div id="suggestions"></div>
        
          <div class="icon"><i class="fas fa-search"></i></div>
        
        </form>

      </div>

      <div id="msg-pesquisa"></div>

      <div id="resultado"></div>

    </div>

</div>  


