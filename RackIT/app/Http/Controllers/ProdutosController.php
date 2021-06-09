<?php

namespace App\Http\Controllers;

use App\Models\armazen;
use App\Models\produto;
use App\Models\categoria;

use App\Models\info_produto;

use App\Models\lista_produto;
use App\Models\produtos_has_categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProdutosController extends Controller
{
    public function index(Request $request)
    {

        $userid = Auth::user()->id;
        // $nomedaslistas = DB::select("select * from lista_produtos inner join users_has_listaprodutos on lista_produtos.id = users_has_listaprodutos.lista_produtos_id inner join users on users.id = users_has_listaprodutos.users_id where users.id = ?", [$userid]);
        $nomedaslistas = DB::select("select lista_produtos.nome as nome, lista_produtos.id as id from lista_produtos inner join users_has_listaprodutos on lista_produtos.id = users_has_listaprodutos.lista_produtos_id inner join users on users.id = users_has_listaprodutos.users_id where users.id = ?", [$userid]);

        $input = $request->all();
        if (!empty($input['SelectListaProdutos'])) {
            $teste = $request->SelectListaProdutos;
        } else {
            foreach ($nomedaslistas as $lista) {
                $teste = $lista->id;
                break;
            }
        }
        // $produto = produto::all()->where('lista_produtos_id', $teste);

        $produto = DB::select("select produtos.id as 'id', produtos.codigoBarras as 'codigoBarras', produtos.nome as 'nome', categorias.nome as 'categoria' from produtos inner join produtos_has_categorias on produtos.id = produtos_has_categorias.produtos_id inner join categorias on categorias.id = produtos_has_categorias.categorias_id where produtos.lista_produtos_id = 1");
        $armazens = armazen::all()->where('lista_produtos_id', $teste);
        $infoproduto = info_produto::all();
        $produtosCategorias = produtos_has_categoria::all()->where('lista_produtos_id', $teste);
        return view('produtos.index', ['produto' => $produto, 'infoproduto' => $infoproduto, 'nomedaslistas' => $nomedaslistas, 'armazens' => $armazens, 'produtosCategorias' => $produtosCategorias]);
    }
    //Pagina create produto existente
    public function showcreate(Request $request)
    {
        $nomeProdutos = DB::select("select produtos.nome, produtos.id from produtos inner join lista_produtos on lista_produtos.id = produtos.lista_produtos_id where produtos.lista_produtos_id=?", [1]);
        $nomeCategoria = DB::select("select categorias.nome,categorias.id from categorias inner join lista_produtos on lista_produtos.id = categorias.lista_produtos_id where lista_produtos_id=?", [1]);
        $nomeArmazem = DB::select("select armazens.nome,armazens.id from armazens inner join lista_produtos on lista_produtos.id = armazens.lista_produtos_id where lista_produtos_id=?", [1]);
        return view('produtos.create', ['nomeProdutos' => $nomeProdutos, 'nomeCategoria' => $nomeCategoria, 'nomeArmazem' => $nomeArmazem]);
    }

    //Pagina create produto novo
    public function showcreatenovo(Request $request)
    {
        // $nomeProdutos = DB::select("select produtos.nome, produtos.id from produtos inner join lista_produtos on lista_produtos.id = produtos.lista_produtos_id where produtos.lista_produtos_id=?", [1]);
        $nomeCategoria = DB::select("select categorias.nome,categorias.id from categorias inner join lista_produtos on lista_produtos.id = categorias.lista_produtos_id where lista_produtos_id=?", [1]);
        $nomeArmazem = DB::select("select armazens.nome,armazens.id from armazens inner join lista_produtos on lista_produtos.id = armazens.lista_produtos_id where lista_produtos_id=?", [1]);


        $userid = Auth::user()->id;
        $nomedaslistas = DB::select("select lista_produtos.nome as nome, lista_produtos.id as id from lista_produtos inner join users_has_listaprodutos on lista_produtos.id = users_has_listaprodutos.lista_produtos_id inner join users on users.id = users_has_listaprodutos.users_id where users.id = ?", [$userid]);
        return view('produtos.createnovo', ['nomeCategoria' => $nomeCategoria, 'nomeArmazem' => $nomeArmazem, 'nomedaslistas' => $nomedaslistas]);
    }

    //Criar produto existente
    public function createInfoProd(Request $request)
    {
        $infoproduto = new info_produto();
        $auxprodutoID = DB::select("select produtos.id from produtos where nome=?", [$request->nome]);
        $auxarmazemID = DB::select("select armazens.id from armazens where nome=?", [$request->armazem]);
        foreach ($auxprodutoID as $aux) {
            $idaux = $aux->id;
            break;
        }

        foreach ($auxarmazemID as $aux) {
            $idArmazemAUX = $aux->id;
            break;
        }
        $infoproduto->produtosID = $idaux;
        $infoproduto->armazemID = $idArmazemAUX;
        $infoproduto->dataCompra = $request->dataCompra;
        $infoproduto->dataValidade = $request->dataValidade;
        $infoproduto->precoCompra = $request->precoCompra;
        $infoproduto->precoNormal = $request->precoNormal;
        $infoproduto->save();
        return redirect()->route('produtos.index');
    }

    //Criar produto novo
    public function createInfoProdNovo(Request $request)
    {
        $produto = new produto();
        $produto->nome = $request->nomeproduto;
        $auxLista = DB::select("select id from armazens where nome=?", [$request->armazem]);
        $produto->codigoBarras = $request->codigoBarras;
        foreach ($auxLista as $listaaux) {
            $produto->lista_produtos_id = $listaaux->id;
            break;
        }
        $produto->save();


        $auxprodutoID = DB::select("select produtos.id from produtos where nome=?", [$produto->nome]);
        $auxcateogiraID = DB::select("select categorias.id from categorias where nome=?", [$request->categoria]);

        foreach ($auxprodutoID as $aux) {
            $idaux = $aux->id;
            break;
        }

        foreach ($auxcateogiraID as $aux) {
            $idcat = $aux->id;
            break;
        }
        $prodCat = new produtos_has_categoria();
        $prodCat->produtos_id = $idaux;
        $prodCat->categorias_id = $idcat;
        $prodCat->save();
        $infoproduto = new info_produto();


        $auxarmazemID = DB::select("select armazens.id from armazens where nome=?", [$request->armazem]);
        foreach ($auxarmazemID as $aux) {
            $idArmazemAUX = $aux->id;
            break;
        }
        $infoproduto->produtosID = $idaux;
        $infoproduto->armazemID = $idArmazemAUX;
        $infoproduto->dataCompra = $request->dataCompra;
        $infoproduto->dataValidade = $request->dataValidade;
        $infoproduto->precoCompra = $request->precoCompra;
        $infoproduto->precoNormal = $request->precoNormal;
        $infoproduto->save();
        return redirect()->route('produtos.index');
    }

    // public function create(Request $request)
    // {
    //     $produto = new produto();
    //     $produto->nome = $request->nome;
    //     $produto->codigoBarras = $request->codigoBarras;
    //     $produto->save();
    //     return redirect()->route('produtos.index');
    // }

    public function showedit(produto $produto)
    {
        $nomeProdutos = DB::select("select produtos.nome, produtos.id from produtos inner join lista_produtos on lista_produtos.id = produtos.lista_produtos_id where produtos.lista_produtos_id=?", [1]);
        $nomeCategoria = DB::select("select categorias.nome,categorias.id from categorias inner join lista_produtos on lista_produtos.id = categorias.lista_produtos_id where lista_produtos_id=?", [1]);
        $nomeArmazem = DB::select("select armazens.nome,armazens.id from armazens inner join lista_produtos on lista_produtos.id = armazens.lista_produtos_id where lista_produtos_id=?", [1]);
        return view('produtos.edit', ['produto' => $produto, 'nomeProdutos' => $nomeProdutos, 'nomeCategoria' => $nomeCategoria, 'nomeArmazem' => $nomeArmazem]);
    }

    public function edit(Request $request, produto $produto)
    {
        // $produto = new produto();
        $produto->nome = $request->nome;
        $produto->codigoBarras = $request->codigoBarras;
        $produto->save();
        return redirect()->route('produtos.index');
    }

    public function delete(produto $produto)
    {
        $produto->delete();
        return redirect()->route('produtos.index');
    }
}
