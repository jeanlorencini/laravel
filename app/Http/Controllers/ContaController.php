<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conta;
use Illuminate\Support\Facades\Validator;

class ContaController extends Controller
{
    public function cadastrar(Request $request)
    {
        // Validação dos dados recebidos
        $validator = Validator::make($request->all(), [
            'jogo' => 'required|max:255',
            'usuario' => 'required|max:255|unique:contas,usuario', // Usuário único na tabela
            'senha' => 'required|max:255'
        ]);
    
        // Em caso de falha na validação, retornar erro
        if ($validator->fails()) {
            return response()->json(['erro' => $validator->errors()], 400);
        }
    
        // Tentativa de criação da conta com os dados validados
        try {
            $conta = Conta::create([
                'jogo' => $request->jogo,
                'usuario' => $request->usuario,
                'senha' => ($request->senha), // Criptografando a senha
                'senha_texto_claro' => $request->senha  // Adicionando a senha em texto claro
            ]);
    
            // Resposta de sucesso
            return response()->json(['mensagem' => 'Conta de jogo cadastrada com sucesso!'], 200);
    
        } catch (\Exception $e) {
            // Em caso de qualquer outra falha, retornar mensagem de erro genérica
            return response()->json(['erro' => 'Erro ao cadastrar a conta de jogo.'], 500);
        }
    }
    

        // Método para buscar jogos
        public function buscarJogos(Request $request)
        {
          try {
        
            $termoBusca = $request->query('jogo');
            $jogos = Conta::where('jogo', 'like', "%{$termoBusca}%")
                           ->distinct()
                           ->pluck('jogo');
        
            return response()->json(['jogos' => $jogos]);
        
          } catch (\Exception $e) {
           
            return response()->json(['erro' => 'Erro na busca de jogos'], 500);
        
          } 
          
        }

    // Método para pesquisar contas
    public function pesquisarContas(Request $request) 
    {
      $jogo = $request->input('jogo');
    
      try {
    
        // Busca as contas associadas ao jogo
        $contas = Conta::where('jogo', $jogo)->get();
    
        if($contas->isEmpty()) {
          return response()->json(['erro' => 'Nenhuma conta encontrada para este jogo.'], 404); 
        }
    
        return response()->json(['contas' => $contas], 200);
    
      } catch (\Exception $e) {
    
        return response()->json(['erro' => 'Erro na pesquisa de contas'], 500);
      
      }
    
    }

    public function listarJogos() 
{
    try {
        $jogos = Conta::distinct()->pluck('jogo');
        return response()->json(['nomes_jogos' => $jogos], 200);
    } catch (\Exception $e) {
        return response()->json(['erro' => 'Erro ao listar jogos'], 500);
    }
}
public function atualizarJogo(Request $request)
{
    $jogoId = $request->id;
    $novoNome = $request->nome;

    $jogo = Jogo::find($jogoId);
    if ($jogo) {
        $jogo->nome = $novoNome;
        $jogo->save();
        return response()->json(['mensagem' => 'Jogo atualizado com sucesso!']);
    }

    return response()->json(['erro' => 'Jogo não encontrado'], 404);
}

public function excluirJogo(Request $request)
{
    $jogoId = $request->id;

    $jogo = Jogo::find($jogoId);
    if ($jogo) {
        $jogo->delete();
        return response()->json(['mensagem' => 'Jogo excluído com sucesso!']);
    }

    return response()->json(['erro' => 'Jogo não encontrado'], 404);
} 

public function atualizarConta(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id' => 'required|exists:contas,id',
        'jogo' => 'required|max:255',
        'usuario' => 'required|max:255',
        'senha' => 'required|max:255'
    ]);

    if ($validator->fails()) {
        return response()->json(['erro' => $validator->errors()], 400);
    }

    try {
        $conta = Conta::find($request->id);
        if (!$conta) {
            return response()->json(['erro' => 'Conta não encontrada.'], 404);
        }

        $conta->jogo = $request->jogo;
        $conta->usuario = $request->usuario;
        $conta->senha = $request->senha; // Considere usar Hash::make($request->senha)
        $conta->save();

        return response()->json(['mensagem' => 'Conta atualizada com sucesso!'], 200);
    } catch (\Exception $e) {
        // Aqui você pode logar o erro para análise posterior
        \Log::error('Erro ao atualizar conta: ' . $e->getMessage());
        return response()->json(['erro' => 'Erro interno no servidor.'], 500);
    }
}
public function excluirConta($id)
{
    try {
        $conta = Conta::findOrFail($id);
        $conta->delete();

        return response()->json(['mensagem' => 'Conta excluída com sucesso!'], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['erro' => 'Conta não encontrada.'], 404);
    } catch (\Exception $e) {
        \Log::error('Erro ao excluir conta: ' . $e->getMessage());
        return response()->json(['erro' => 'Erro interno no servidor.'], 500);
    }
}


}