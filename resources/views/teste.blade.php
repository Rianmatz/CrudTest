<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    @guest

    <h1>Crie a sua propia tarefa</h1>
    
    <section id="front">
        <div>
            <h2>Logue em sua conta para começar as atividades</h2>
        </div>
        <div id="login">
            <button onclick="window.location.href='/login';">Login</button>
            <button onclick="window.location.href='/register';">Cadastrar</button>
        </div>
    </section>

    @endguest

    @auth
        <header>
            <div id= "middle">
                <h2>Criar Nova Tarefa</h2>
                <div id="criar">
                    <form action="/tasks" method="post">
                        @csrf
                        <div class="duo">
                            <label for="name">Nome</label>
                            <input type="text" id="name" name="title">
                            @error('title')
                            <p class="errors">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="duo">
                            <label for="description">Descrição</label>
                            <input type="text" id="description" name="description">
                        </div>
                        <div class="duo">
                            <label for="date">Prazo</label>
                            <input type="date" id="date" name="date">
                            @error('date')
                                <p class="errors">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="duo">
                            <label for="priority">Prioridade</label>
                            <select name="priority" id="priority">
                                <option value="Baixa">Baixa</option>
                                <option value="Media">Media</option>
                                <option value="Alta">Alta</option>
                            </select>
                        </div>
                        <input type="submit" value="Criar tarefa" id="criartarefa">
                    </form>
                </div>
            </div>

            <div id="conta">
                <span id="logado">Logado em: {{ auth()->user()->name }}</span>
                <div id="deslogar">
                    <form action="/logout" method="post">
                        @csrf
                        <button>Sair da conta</button>
                    </form>
                </div>
            </div>
        </header>

   @if(session('msg'))

    <h2 id="mensagem">{{ session('msg') }}</h2>

   @endif
    
        <section id="section">
            <div id="left">
                <div class="buscando">
                    <h2>Tarefas Pendentes</h2>
                    <form action="/" method="GET">
                        <input type="text" id="searchPending" name="searchPending" placeholder="Busque uma tarefa..." value="{{ request('searchPending') }}">
                        <button type="submit" class="buscar">Buscar</button>
                        @if (request('searchPending'))
                            <button type="button" class="limpar" onclick="window.location.href='/'">Limpar</button>
                        @endif
                    </form>    
                </div>

                <div id="listadas">
                    
                    @forelse ($pending as $task)

                        <div class="umatask">

                            <div id="top" >
                                <div class="itens">
                                    <div class="conteudo" >
                                        <h3>Nome</h3>
                                        <p class="titulo">{{ $task->title }}</p>
                                    </div>
                                    <div class="conteudo">
                                        <h3>Descrição</h3>
                                        <p class="descrição">{{ $task->description}}</p>
                                    </div>
                                    
                                    <div class="conteudo">
                                        <h3>Prioridade</h3>
                                        <p class="prioridade {{ $task->priority }}">
                                        {{ ($task->priority) }}</p>
                                    </div>
                                    <div class="conteudo">
                                        <h3>Prazo</h3>
                                        <p class="data">{{ $task->date?->format('d/m/Y')}}</p>
                                    </div>

                                    <div class="conteudo">
                                        <h3>Progresso</h3>
                                        <form class="progresso" action="/tasks/{{ $task->id }}/progress" method="post">
                                        @csrf
                                        @method('PATCH')
                                        <input type="range" name="progress" min="0" max="100"
                                            value="{{ $task->progress }}"
                                            oninput="this.nextElementSibling.textContent = this.value"
                                            onchange="this.form.submit()">
                                            <output>{{ $task->progress }}</output>
                                        </form>
                                    </div>

                                </div>
                                <form action="/tasks/{{ $task->id }}/complete" method="post">
                                @csrf
                                @method('PATCH')
                                <input type="submit" class="concluir" value="Concluir">
                                </form>
                                <form action="/tasks/{{ $task->id }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Remover" class="remover">
                                </form>
                                <button type="button" class="editar" onclick="document.getElementById('edit-edit{{ $task->id }}').classList.toggle('aberto')">Editar</button>
                            </div>

                            <div id="edit-edit{{ $task->id }}"
                                class="formulario-editor @if ($errors->edit->any() && old('editing') == $task->id) aberto @endif">

                            <form action="/tasks/{{ $task->id }}" method="post" >
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="editing" value="{{ $task->id }}">
            
                            <div class="duo">
                                <label for="title-edit{{ $task->id }}">Nome</label>
                                <input type="text" name="title" id="title-edit{{ $task->id }}" value="{{ old('editing') == $task->id ? old('title') : ($task->title)}}">
                                @if (old('editing') == $task ->id)
                                    @error('title', 'edit')  <span class="errors">{{ $message }}</span>
                                    @enderror
                                @endif
                            </div>
            
                            <div class="duo">
                                <label for="description-edit{{ $task->id }}">Descrição</label>
                                <input type="text" name="description" id="description-edit{{ $task->id }}" value="{{ old('editing') == $task->id ? old('description') : ($task->description)}}">
                            </div>
            
                            <div class="duo">
                                <label for="date-edit{{ $task->id }}">Prazo</label>
                                <input type="date" name="date" id="date-edit{{ $task->id }}" value="{{ old('editing') == $task->id ? old('date') : $task->date?->format('d/m/Y')}}">
                                @if (old('editing') == $task ->id)
                                    @error('date', 'edit')  <span class="errors">{{ $message }}</span>
                                    @enderror
                                @endif
                            </div>

                            <div class="duo">
                                <label for="priority-edit{{ $task->id }}">Prioridade</label>
                                <select name="priority" id="priority-id{{ $task->id }}">
                                   @foreach (['Alta' => 'Alta', 'Media' => 'Media', 'Baixa' => 'Baixa'] as $valor => $nome)
                                    <option value="{{ $valor }}"
                                        @selected((old('editing') == $task->id ? old('priority') : $task->priority) == $valor)>
                                        {{ $nome }}
                                    </option>
                                    @endforeach
                                </select>
                                @if (old('editing') == $task ->id)
                                    @error('title', 'edit')  <span class="errors">{{ $message }}</span>
                                    @enderror
                                @endif
                            </div>
                                <button type="submit" class="editar" >Salvar</button>    
                            </form>
                            </div>

                        </div>
                        @empty
                            <p class="vazia">
                                @if (request('searchPending'))
                                    Nenhuma tarefa encontrada com o nome "{{ request('searchPending') }}".
                                @else
                                    Você não tem tarefas pendentes.
                                @endif
                            </p>
                        @endforelse
                </div>              
            </div>

            <div id="right">

                <h2>Tarefas concluidas</h2>

                <form action="/" method="GET">
                    <input type="text" id="searchComplete" name="searchComplete" placeholder="Busque uma tarefa..." value="{{ request('searchComplete') }}">
                    <button type="submit" class="buscar">Buscar</button>
                    @if (request('searchComplete'))
                        <button type="button" class="limpar" onclick="window.location.href='/'">Limpar</button>
                    @endif
                </form>
                
                    <div id="completas">
                        @forelse ($complete as $task)
                            <div class="umacompleta">
                                <div class="itens">
                                    <div class="conteudo" >
                                        <h3>Nome</h3>
                                        <p class="titulo">{{ $task->title }}</p>
                                    </div>
                                    <div class="conteudo">
                                        <h3>Descrição</h3>
                                        <p class="descrição">{{ $task->description}}</p>
                                    </div>
                                    
                                    <div class="conteudo">
                                        <h3>Data de conclusão</h3>
                                        <p class="dataconcluida">{{ $task->complete_in?->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="conteudo">
                                        <h3>Prazo</h3>
                                        <p class="data">{{ $task->date?->format('d/m/Y')}}</p>
                                    </div>
                                    <form action="/tasks/{{ $task->id }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                        <input type="submit" value="Remover" class="remover">
                                    </form>
                                </div>
                            </div>    
                                @empty
                                <p class="vazia">
                                @if (request('searchComplete'))
                                    Nenhuma tarefa encontrada com o nome "{{ request('searchComplete') }}".
                                @else
                                    Você não tem tarefas concluidas.
                                @endif
                                </p>
                        @endforelse
                    </div>
                </div>
        </section>
    @endauth


   

</body>
</html>