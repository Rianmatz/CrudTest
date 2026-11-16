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

        <div id="conta">
            <span id="logado">Logado em: {{ auth()->user()->name }}</span>
            <div id="deslogar">
                <form action="/logout" method="post">
                    @csrf
                    <button>Sair da conta</button>
                </form>
            </div>
        </div>
        <h1>Listas de tarefas</h1>

   @if(session('msg'))

    <h2 id="mensagem">{{ session('msg') }}</h2>

   @endif
    
        <section id="section">
            <div id="left">
                <h2>Tarefas listadas</h2>
                <form action="/" method="GET">

                    <input type="text" id="searchPending" name="searchPending" placeholder="Busque uma tarefa..." value="{{ request('searchPending') }}">

                    <button type="submit" class="buscar">buscar</button>

                    @if (request('searchPending'))
                        <button type="button" class="limpar" onclick="window.location.href='/'">Limpar</button>
                    @endif

                </form>
                <div id="listadas">
                    
                    @forelse ($pending as $task)

                    <div class="umatask">
                            <p class="titulo">{{ $task->title }}</p>
                            <p class="descrição">{{ $task->description}}</p>
                            <p class="data">Prazo até: {{ $task->date?->format('d/m/Y')}}</p>
            
                            <button type="button" class="editar" onclick="document.getElementById('edit-edit{{ $task->id }}').classList.toggle('aberto')">Editar</button>

                        <div id="edit-edit{{ $task->id }}"
                            class="formulario-editor @if ($errors->edit->any() && old('editing') == $task->id) aberto @endif">

                        <form action="/tasks/{{ $task->id }}" method="post">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="editing" value="{{ $task->id }}">
            
                            <label for="title-edit{{ $task->id }}">Nome</label>
                            <input type="text" name="title" id="title-edit{{ $task->id }}" value="{{ old('editing') == $task->id ? old('title') : ($task->title)}}">
                            @if (old('editing') == $task ->id)
                                @error('title', 'edit')  <span class="errors">{{ $message }}</span>
                                @enderror
                            @endif
            
                            <label for="description-edit{{ $task->id }}">Descrição</label>
                            <input type="text" name="description" id="description-edit{{ $task->id }}" value="{{ old('editing') == $task->id ? old('description') : ($task->description)}}">
            
                            <label for="date-edit{{ $task->id }}">Data</label>
                            <input type="date" name="date" id="date-edit{{ $task->id }}" value="{{ old('editing') == $task->id ? old('date') : $task->date?->format('d/m/Y')}}">
                            @if (old('editing') == $task ->id)
                                @error('date', 'edit')  <span class="errors">{{ $message }}</span>
                                @enderror
                            @endif
            
                            <button type="submit" class="editar" >Salvar</button>

                        </form>
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

                    </div>

                    @empty
                        @if (request('searchPending'))
                            Nenhuma tarefa encontrada com o nome "{{ request('searchPending') }}".
                        @else
                            <p class="vazia">Voce ainda nao tem tarefas pendentes.</p>
                        @endif
                    @endforelse

                </div>
            </div>
            <div id= "middle">

                <h2>Criar Nova Tarefa</h2>

                <div id="criar">

                    <form action="/tasks" method="post">
                        @csrf
                        <label for="name">Nome</label>
                            <input type="text" id="name" name="title">
                            @error('title')
                            <p class="errors">{{ $message }}</p>
                            @enderror
                        <label for="description">Descrição</label>
                            <input type="text" id="description" name="description">
                        <label for="date">Prazo ate:</label>
                            <input type="date" id="date" name="date">
                            @error('date')
                            <p class="errors">{{ $message }}</p>
                            @enderror
                            <input type="submit" value="Criar tarefa" class="criartarefa">
                    </form>

                </div>
            </div>
            <div id="right">

                <h2>Tarefas concluidas</h2>

                <form action="/" method="GET">
                    <input type="text" id="searchComplete" name="searchComplete" placeholder="Busque uma tarefa..." value="{{ request('searchComplete') }}">
                    <button type="submit" class="buscar">buscar</button>
                    @if (request('searchComplete'))
                        <a href="/">Limpar</a>
                    @endif
                </form>
                
                <div id="completas">
                    @forelse ($complete as $task)
                        <div class="umacompleta">
                            <p class="titulo">{{ $task->title }}</p>
                            <p class="descrição">{{ $task->description}}</p>
                            <p class="data"> Prazo até: {{ $task->date?->format('d/m/Y')}}</p>
                            <p class="dataconcluida">Concuida em: {{ $task->complete_in?->format('d/m/Y') }}</p>
                            <form action="/tasks/{{ $task->id }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Remover" class="remover">
                            </form>
                        </div>    
                            @empty
                            <p class="vazia">
                                @if (request('searchComplete'))
                                    Nenhuma tarefa encontrada com o nome "{{ request('searchComplete') }}".
                                @else
                                    Voce ainda nao tem tarefas concluidas.
                                @endif
                            </p>
                    @endforelse
                </div>
            </div>
        </section>
    @endauth


   

</body>
</html>