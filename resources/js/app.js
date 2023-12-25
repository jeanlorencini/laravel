import 'flowbite';
import { createApp } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import '@fortawesome/fontawesome-free/css/all.css';

document.addEventListener('DOMContentLoaded', function() {

    createInertiaApp({
        resolve: name => {
          const page = {
            'Dashboard': Dashboard,
            'Login': Login
          }[name];
          return page;
        },
        setup({ el, app, props, plugin }) {
          createApp({ render: () => h(app, props) })
            .use(plugin)
            .mount(el);
        },
      });

      const formCadastro = document.getElementById("form-cadastro");
      const btnCadastrar = document.getElementById("btn-cadastrar");
      const msgCadastro = document.getElementById("msg-cadastro");
      const jogoInput = document.getElementById("jogoInput"); // Campo de pesquisa
      const suggestionsContainer = document.getElementById('suggestions');
      const msgPesquisa = document.getElementById("msg-pesquisa");
      const resultado = document.getElementById("resultado");

    /**
     * Fetches list of games from server and populates the game select dropdown.
     * Allows user to select existing game or enter a new game.
     * Shows/hides new game input field based on selection.
     */
    function preencherJogoSelect() {
        let jogoSelect = document.getElementById("jogo-select");
        let novoJogoInput = document.getElementById('novo-jogo-input');

        fetch("/listar-jogos", {
            method: "GET",
            headers: {
                "Accept": "application/json"
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.nomes_jogos) {
                    jogoSelect.innerHTML = "<option value=''>Selecione um jogo</option>";
                    let opcaoNovoJogo = document.createElement("option");
                    opcaoNovoJogo.value = "novo";
                    opcaoNovoJogo.textContent = "Novo Jogo";
                    jogoSelect.appendChild(opcaoNovoJogo);

                    data.nomes_jogos.forEach(jogo => {
                        let option = document.createElement("option");
                        option.value = jogo;
                        option.textContent = jogo;
                        jogoSelect.appendChild(option);
                    });

                    $(jogoSelect).select2({
                        //theme: "tailwind"
                    });
                    $(jogoSelect).on('select2:select', function (e) {
                        if (e.params.data.id === 'novo') {
                            novoJogoInput.style.display = 'block';
                        } else {
                            novoJogoInput.style.display = 'none';
                        }
                    });
                }
            })
            .catch(err => console.error(err));
    }

    // Evento de 'input' para sugerir jogos durante a digitação
    if (jogoInput) {
        jogoInput.addEventListener('input', function(e) {
            let inputValue = e.target.value;
            if (inputValue.length >= 3) {
                fetch('/buscar-jogos?jogo=' + encodeURIComponent(inputValue))
                .then(res => res.json())
                .then(data => {
                    suggestionsContainer.innerHTML = '';
                    data.jogos.forEach(jogo => {
                        let div = document.createElement('div');
                        div.textContent = jogo;
                        div.className = 'suggestion-item';
                        div.addEventListener('click', function() {
                            jogoInput.value = jogo;
                            suggestionsContainer.innerHTML = '';
                            pesquisarContas();
                        });
                        suggestionsContainer.appendChild(div);
                    });
                })
                .catch(err => console.error(err));
            } else {
                suggestionsContainer.innerHTML = '';
            }
        });
         // Adicionando evento para interceptar o ENTER
         jogoInput.addEventListener('keydown', function(e) {
            if (e.keyCode === 13) { // Verifica se a tecla pressionada é a tecla ENTER
                e.preventDefault();  // Previne a ação padrão (envio do formulário)
            }
        });
    }
    
    function cadastrarConta(event) {
        event.preventDefault();
    
        let jogoSelect = document.getElementById("jogo-select");
        let jogo = jogoSelect.value;
        let novoJogo = document.getElementById('novo-jogo-input').value;
        let usuario = document.getElementById("usuario").value;
        let senha = document.getElementById("senha").value;
    
        if (jogo === 'novo' && novoJogo) {
            jogo = novoJogo;
        }
    
        const conta = { jogo, usuario, senha };
    
        fetch("/cadastrar-conta", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(conta)
        })
        .then(res => res.json())
        .then(data => {
            console.log("Resposta do servidor:", data);
            if (data.erro) {
                msgCadastro.textContent = "Erro ao cadastrar: " + JSON.stringify(data.erro);
                msgCadastro.style.backgroundColor = "red";
            } else {
                msgCadastro.textContent = "Conta cadastrada com sucesso!";
                msgCadastro.style.backgroundColor = "green";
                formCadastro.reset();
                preencherJogoSelect();
            }
        })
        
        .catch(err => {
            console.error('Erro ao cadastrar conta: ', err);
            msgCadastro.textContent = "Erro de conexão ao tentar cadastrar.";
            msgCadastro.style.backgroundColor = "red";
                // Adicionando eventos aos botões
    if (btnCadastrar) {
        btnCadastrar.addEventListener("click", cadastrarConta);
    }
    if (btnPesquisar) {
        btnPesquisar.addEventListener("click", pesquisarContas);
    }
        });
    }
    
    document.getElementById("btn-cadastrar").addEventListener("click", cadastrarConta);
    

    function pesquisarContas() {
        const jogo = jogoInput.value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    
        fetch("/pesquisar-contas", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ jogo })
        })
        .then(res => res.json())
        .then(data => {
            if (data.contas && data.contas.length > 0) {
                msgPesquisa.style.backgroundColor = "green";
                msgPesquisa.textContent = "Contas encontradas.";
                resultado.innerHTML = "";
                let table = document.createElement("table");
                let thead = document.createElement("thead");
                let tbody = document.createElement("tbody");
    
                let tr = document.createElement("tr");
                let th1 = document.createElement("th");
                let th2 = document.createElement("th");
                let th3 = document.createElement("th");
                let th4 = document.createElement("th");
    
                th1.textContent = "Jogo";
                th2.textContent = "Usuário";
                th3.textContent = "Senha";
                th4.textContent = "Ações";
    
                tr.appendChild(th1);
                tr.appendChild(th2);
                tr.appendChild(th3);
                tr.appendChild(th4);
                thead.appendChild(tr);
                table.appendChild(thead);
    
                data.contas.forEach(conta => {
                    let tr = document.createElement("tr");
                    tr.setAttribute("data-conta-id", conta.id);

                    let td1 = document.createElement("td");
                    td1.textContent = conta.jogo;

                    let td2 = document.createElement("td");
                    td2.textContent = conta.usuario;

                    let td3 = document.createElement("td");
                    td3.textContent = conta.senha;
                    
                    let btnCopiar = document.createElement("button");
                    btnCopiar.innerHTML = `<i class="fas fa-copy"></i>`; // Ícone do Font Awesome
                    btnCopiar.className = "relative align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs bg-blue-500 text-white shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none";
                    btnCopiar.style.position = "relative"; // Posicionamento relativo para o tooltip
            
                    let tooltip = document.createElement("div");
                    tooltip.className = "absolute z-50 whitespace-normal break-words rounded-lg bg-black py-1.5 px-3 font-sans text-sm font-normal text-white focus:outline-none";
                    tooltip.textContent = "Copy";
                    tooltip.style.visibility = "hidden"; // Inicialmente oculto
                    tooltip.style.position = "absolute";
                    tooltip.style.bottom = "100%"; // Posiciona acima do botão
                    tooltip.style.left = "50%";
                    tooltip.style.transform = "translateX(-50%)";
                    tooltip.style.marginBottom = "5px"; // Espaço entre o tooltip e o botão
                    btnCopiar.appendChild(tooltip);
            
                    btnCopiar.addEventListener("mouseover", function() {
                        tooltip.textContent = "Copie";
                        tooltip.style.visibility = "visible";
                    });
            
                    btnCopiar.addEventListener("mouseout", function() {
                        tooltip.style.visibility = "hidden";
                    });
            
                    btnCopiar.onclick = function() {
                        navigator.clipboard.writeText(`Jogo: ${conta.jogo}\nUsuário: ${conta.usuario}\nSenha: ${conta.senha}`);
                        tooltip.textContent = "Copiado";
                        tooltip.style.visibility = "visible";
                        setTimeout(() => {
                            tooltip.style.visibility = "hidden";
                        }, 2000);
                    };

                    let td4 = document.createElement("td");
                    td4.appendChild(btnCopiar);

                    let btnEditar = document.createElement("button");
                    btnEditar.innerHTML = `<i class="fas fa-edit"></i>`; // Ícone do Font Awesome
                    btnEditar.className = "btn-editar relative align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs bg-amber-500 text-black shadow-md shadow-amber-500/20 hover:shadow-lg hover:shadow-amber-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none";
                    btnEditar.setAttribute("data-editing", "false"); // Adiciona um atributo para controlar o estado de edição
                    btnEditar.onclick = function() {
                        editarConta(tr, document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    };

                    let btnExcluir = document.createElement("button");
                    btnExcluir.innerHTML = `<i class="fas fa-trash-alt"></i>`; // Ícone do Font Awesome
                    btnExcluir.className = "btn-excluir relative align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs bg-red-500 text-white shadow-md shadow-red-500/20 hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none"; // Adicione as classes Tailwind conforme necessário
                    btnExcluir.onclick = function() {
                        excluirConta(conta.id, csrfToken);
                    };

                    td4.appendChild(btnEditar);
                    td4.appendChild(btnExcluir);

                    tr.appendChild(td1);
                    tr.appendChild(td2);
                    tr.appendChild(td3);
                    tr.appendChild(td4);

                    tbody.appendChild(tr);
                });

                table.appendChild(tbody);
            resultado.appendChild(table);
        } else {
            msgPesquisa.style.backgroundColor = "red";
            msgPesquisa.textContent = "Nenhuma conta encontrada para este jogo.";
            resultado.innerHTML = "";
        }
    })
    .catch(err => {
        msgPesquisa.style.backgroundColor = "red";
        msgPesquisa.textContent = 'Erro ao pesquisar contas: ' + err.message;
        resultado.innerHTML = "";
    });
}

    function editarConta(tr, csrfToken) {
        console.log(csrfToken);
        let tdJogo = tr.children[0];
        let tdUsuario = tr.children[1];
        let tdSenha = tr.children[2];
        let btnEditar = tr.children[3].querySelector(".btn-editar");
        let isEditing = btnEditar.getAttribute("data-editing") === "true";
    

        if (!tdJogo || !tdUsuario || !tdSenha || !btnEditar) {
            console.error("Um ou mais elementos necessários não foram encontrados.");
            return;
        }
    
        if (!isEditing) {
            // Inicia a edição
            tdJogo.innerHTML = `<input type="text" value="${tdJogo.textContent}">`;
            tdUsuario.innerHTML = `<input type="text" value="${tdUsuario.textContent}">`;
            tdSenha.innerHTML = `<input type="text" value="${tdSenha.textContent}">`;
            btnEditar.innerHTML = `<i class="fas fa-save"></i>`; // Muda o ícone para 'salvar'
            btnEditar.setAttribute("data-editing", "true");
        } else {
            let inputJogo = tdJogo.querySelector("input");
            let inputUsuario = tdUsuario.querySelector("input");
            let inputSenha = tdSenha.querySelector("input");
        
            if (!inputJogo || !inputUsuario || !inputSenha) {
                console.error("Não foi possível encontrar os campos de entrada.");
                return;
            }
        
            let jogoAtualizado = inputJogo.value;
            let usuarioAtualizado = inputUsuario.value;
            let senhaAtualizada = inputSenha.value;
            let contaId = tr.getAttribute("data-conta-id");
            console.log(csrfToken);
            fetch("/atualizar-conta", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({ 
                    id: contaId,
                    jogo: jogoAtualizado,
                    usuario: usuarioAtualizado,
                    senha: senhaAtualizada
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.mensagem) {
                    alert(data.mensagem);
                    pesquisarContas(); // Recarrega os dados após a atualização
                } else {
                    alert("Erro ao atualizar a conta.");
                }
            })
            .catch(err => {
                console.error("Erro ao atualizar: ", err);
            });
            btnEditar.innerHTML = `<i class="fas fa-edit"></i>`; // Muda o ícone de volta para 'editar'
        btnEditar.setAttribute("data-editing", "false");
        }
    }
    

    function excluirConta(id, csrfToken) {
        if (confirm("Tem certeza que deseja excluir esta conta?")) {
            fetch(`/excluir-conta/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.mensagem) {
                    alert(data.mensagem);
                    pesquisarContas(); // Recarrega os dados após a exclusão
                } else {
                    alert("Erro ao excluir a conta.");
                }
            })
            .catch(err => {
                console.error("Erro ao excluir: ", err);
            });
        }
    }

    preencherJogoSelect();
});
