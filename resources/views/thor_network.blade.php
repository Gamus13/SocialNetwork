<x-app-layout>
    <x-slot name="header">
        @if((Auth::user()->role) == 'admin')
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        @endif
    </x-slot>

    <div class="container">
        <!-- Left menu -->
        <div class="left">
                <a class="profile">
                    <div class="profile-photo">
                        <img class="h-15 w-15 rounded-full object-cover" src="{{ (Auth::user()->profile_photo_path !== null) ? Storage::url(Auth::user()->profile_photo_path) : './images/user_circle.svg'  }}" alt="{{ Auth::user()->fisrt_name }}" />
                    </div>
                    <div class="handle">
                        <h4>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                        <p class="text-muted">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <a href="{{ route('network') }}" class="menu-item active">
                    <span><i class="uil uil-home"></i></span>
                    <h3>Accueil</h3>
                </a>

                @if((Auth::user()->role) == 'admin')
                    <a class="menu-item" href="{{ route('dashboard') }}">
                        <span><i class="uil uil-chart-line"></i></span>
                        <h3>Statistics</h3>
                    </a>
                @endif
                <a class="menu-item" id="theme">
                    <span><i class="uil uil-palette"></i></span>
                    <h3>Themes</h3>
                </a>
                <a class="menu-item" id="theme">
                    <span><i class="uil uil-comments"></i></span>
                    <h3>Messages</h3>
                </a>
                <a class="menu-item" id="theme">
                    <span><i class="uil uil-users-alt"></i></span>
                    <h3>Groupes</h3>
                </a>
                <a class="menu-item" id="theme">
                    <span><i class="uil uil-skip-forward-alt"></i></span>
                    <h3>Video</h3>
                </a>
                <a class="menu-item" id="theme">
                    <span><i class="uil uil-bookmark-full"></i></span>
                    <h3>Enregistrements</h3>
                </a>
                <a class="menu-item" id="theme">
                    <span><i class="uil uil-shop"></i></span>
                    <h3>Marketplace</h3>
                </a>
            </div>

            {{-- <a href="#createPost" class="btn btn-primary" data-bs-toggle="modal">Create post</a> --}}
        </div>

        <!-- Middle -->
        <div class="middle">

            @include('modals.network.addPost')

            <div class="addPost flex justify-around">
                <div class="profile-photo">
                    <img src="{{ (Auth::user()->profile_photo_path !== null) ? Storage::url(Auth::user()->profile_photo_path) : './images/user_circle.svg'  }}">
                </div>

                <a href="#createPost" class="create-post" data-bs-toggle="modal">
                    <input type="text" name="text" placeholder="Quoi de neuf?" id="create-post">
                </a>
            </div>

            <!--- Flux --->
            <div class="feeds">
                <!--- Feed --->
                @foreach($posts as $post)
                    <div class="feed">
                        <div class="head">
                            <div class="user">
                                <div class="profile-photo">
                                    {{-- <img src="{{ (Auth::user()->profile_photo_path !== null) ? Storage::url(Auth::user()->profile_photo_path) : './images/user_circle.svg'  }}" alt="{{ Auth::user()->first_name }}"> --}}
                                    <img class="profile-photo" src="{{ ($post->user->profile_photo_path !== null) ? Storage::url($post->user->profile_photo_path) : './images/user_circle.svg' }}" alt="{{ $post->user->first_name }}" />
                                </div>
                                <div class="info">
                                    <h3>
                                        {{ $post->user->first_name }} {{ $post->user->last_name }}

                                    </h3>
                                    <small>{{ $post->updated_at }}</small>
                                </div>
                            </div>
                            <span class="edit">
                                <div class="dropdown pe-2 d-flex align-items-center">
                                    <a href="#" class="nav-link text-xl text-dark fw-bold p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="uil uil-ellipsis-h cursor-pointer"></i>
                                    </a>
                                    @if((Auth::user()->id == $post->user_id) && (Auth::user()->role == "admin"))
                                        <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                                            <li class="mb-2">
                                                <a class="dropdown-item border-radius-md" href="#updatePost-{{ $post->id }}" data-bs-toggle="modal"><span class="text-primary">Update</span></a>
                                            </li>
                                            <li class="mb-2">
                                                <a class="dropdown-item border-radius-md" href="#changePostStatus-{{ $post->id }}" data-bs-toggle="modal"><span class="text-warning">Block</span></a>
                                            </li>
                                            <li class="mb-1">
                                                <a class="dropdown-item border-radius-md" href="#deletePost-{{ $post->id }}" data-bs-toggle="modal"><span class="text-danger">Delete</span></a>
                                            </li>
                                        </ul>
                                    @elseif(Auth::user()->id == $post->user_id)
                                        <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                                            <li class="mb-2">
                                                <a class="dropdown-item border-radius-md" href="#updatePost-{{ $post->id }}" data-bs-toggle="modal"><span class="text-primary">Update</span></a>
                                            </li>
                                            <li class="mb-1">
                                                <a class="dropdown-item border-radius-md" href="#deletePost-{{ $post->id }}" data-bs-toggle="modal"><span class="text-danger">Delete</span></a>
                                            </li>
                                        </ul>
                                    @elseif((Auth::user()->id != $post->user_id) && (Auth::user()->role == "admin"))
                                        <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                                            <li class="mb-2">
                                                <a class="dropdown-item border-radius-md" href="#changePostStatus-{{ $post->id }}" data-bs-toggle="modal"><span class="text-warning">Block</span></a>
                                            </li>
                                            <li class="mb-1">
                                                <a class="dropdown-item border-radius-md" href="#deletePost-{{ $post->id }}" data-bs-toggle="modal"><span class="text-danger">Delete</span></a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                            </span>
                        </div>

                        @include('modals.network.updatePost')

                        @include('modals.network.deletePost')

                        @include('modals.network.changePostStatus')

                        @if(isset($post->text_post, $post->media_file))
                            <div class="textContent">
                                {{ $post->text_post }}
                            </div>


                            <div class="photo">
                                @if ($post->media_type === 'image')
                                    <img src="{{ Storage::url($post->media_file) }}" alt="post_image">
                                    <script>
                                        console.log("Requête pour l'image exécutée");
                                    </script>
                                @elseif ($post->media_type === 'video')
                                    <video controls>
                                        <source src="{{ Storage::url('files/videos/' . $post->media_file) }}" type="{{ $post->media_mime }}">
                                        Votre navigateur ne prend pas en charge la lecture de vidéos.
                                    </video>
                                    <script>
                                        console.log("Requête pour la vidéo exécutée");
                                    </script>
                                @endif
                            </div>




{{--
                            <div class="liked-by">
                                @foreach($users as $user)
                                    @foreach($comments as $comment)
                                        @if(($post->id == $comment->post_id) && ($user->id == $comment->user_id))
                                            <span>
                                                <img src="{{ ($user->profile_photo_path !== null) ? Storage::url($user->profile_photo_path) : './images/user_circle.svg'  }}">
                                            </span>
                                        @endif
                                    @endforeach
                                @endforeach
                                <p>Commenter par  <b></b> et <b>2, 323 others</b></p>
                            </div>

                            <div class="caption">
                                <p>
                                    <b>Lana Rose</b> {{ $post->text_post }}

                                    @foreach($users as $user)
                                        <span class="harsh-tag">
                                            @if($user->id == $post->user_id)
                                                #{{ $user->first_name }}
                                            @endif
                                        </span>
                                    @endforeach
                                </p>
                            </div> --}}
                        @elseif(isset($post->text_post))
                            <div class="textContentOnly">
                                {{ $post->text_post }}
                            </div>



                            <div class="liked-by">
                                <span><img src="./images/profile-10.jpg"></span>
                                <span><img src="./images/profile-4.jpg"></span>
                                <span><img src="./images/profile-15.jpg"></span>
                                <p>Comment by <b>Ernest HAchiever</b> and <b>2, 323 others</b></p>
                            </div>

                            <div class="caption">
                                <p>
                                    <b>Lana Rose</b> {{ $post->text_post }}

                                    @foreach($users as $user)
                                        <span class="harsh-tag">
                                            @if($user->id == $post->user_id)
                                                #{{ $user->first_name }}
                                            @endif
                                        </span>
                                    @endforeach
                                </p>
                            </div>
                            {{-- c'est ici qu'est afficher les publications des utilisateurs --}}
                        @elseif(isset($post->media_file))
                            <div class="photo">
                                <img src="{{ Storage::url($post->media_file) }}" alt="post_image">
                            </div>
                            {{-- ceci est le blo responsable des like commentaire et partage --}}
                             <hr style="color: #65676B;">
                                <div style="heigt: 61px; ">
                                    <div style="heigt: 41px; display: flex;justify-content: space-around; margin-top:12px; ">

                                        <i class="uil uil-heart" style="color: #65676B; font-size: 21px; font-family: 'segoe UI Historie'; line-height: 1; cursor: pointer">Like</i>
                                        <i class="uil uil-comment-alt-dots" style="color: #65676B; font-family: 'segoe UI Historie'; font-size: 21px; line-height: 1; cursor: pointer">Commenter</i>

                                        <i class="uil uil-arrow-up-right" style="color: #65676B;font-weight: 900px; font-family: 'segoe UI Historie'; font-size: 21px; line-height: 1; cursor: pointer">Partager</i>



                                    </div>

                                </div>
                             <hr style="color: #65676B;margin-top:14px;">

                            {{-- ceci c'est la partie responsable de ceux qui ont commenter le post --}}
                            <div class="liked-by">
                                <span><img src="./images/profile-10.jpg"></span>
                                <span><img src="./images/profile-4.jpg"></span>
                                <span><img src="./images/profile-15.jpg"></span>
                                <p>Comment by <b>Ernest HAchiever</b> and <b>2, 323 others</b></p>
                            </div>

                            <div class="caption">
                                <p>
                                    <b>Lana Rose</b> {{ $post->text_post }}

                                    @foreach($users as $user)
                                        <span class="harsh-tag">
                                            @if($user->id == $post->user_id)
                                                #{{ $user->first_name }}
                                            @endif
                                        </span>
                                    @endforeach
                                </p>
                            </div>
                        @endif
                        {{-- <hr style="color: #65676B;margin-top:14px;"> --}}

                        {{-- bloc responsable du commentaire ie photo et commentaire --}}
                        <div class="action-buttons">
                            <div class="interaction-buttons">
                                <div class="profile-photo">
                                    <img src="{{ (Auth::user()->profile_photo_path !== null) ? Storage::url(Auth::user()->profile_photo_path) : './images/user_circle.svg'  }}">
                                </div>
                                <div class="interaction-btns">
                                    <form method="post" action="{{ route('comments.store') }}">
                                        @csrf

                                        <span >
                                            <div class="interaction-btns">
                                                <input type="text" id="comment" name="comment" placeholder="ajouter un commentaire..." class="add-comment">
                                                <input type="hidden" name="post_id" id="post_id" value="{{ $post->id }}">
                                                <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                                                <button type="submit" class="btn btn-primary"><i class="uil uil-message" ></i></button>
                                            </div>


                                        </span>
                                    </form>
                                </div>

                            </div>
                        </div>

                        <!-- Show comments -->
                        {{-- @foreach($comments as $comment) --}}
                        @foreach($comments->take(0) as $comment)
                            @if (isset($comment) && ($comment->post_id == $post->id))
                                <div class="row mb-2 d-flex justify-content-between">
                                    <div class="col-auto text-start"></div>
                                    <div class="col-auto w-80 p-1 me-3 text-end rounded-4 show-comment">
                                        @foreach($users as $user)
                                            @if(($post->id == $comment->post_id) && ($user->id == $comment->user_id))
                                                <span>
                                                    <img src="{{ ($user->profile_photo_path !== null) ? Storage::url($user->profile_photo_path) : './images/user_circle.svg'  }}">
                                                </span>

                                                <p class="text-sm comment">{{ $comment->comment }}</p>
                                            @endif
                                        @endforeach
                                        <div class="dropdown pe-2 d-flex align-items-center">
                                            <a href="#" class="nav-link text-xl text-white p-0 ms-5" id="dropdownMenuButton-{{ $comment->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="uil uil-ellipsis-h cursor-pointer"></i>
                                            </a>
                                            @if((Auth::user()->id == $comment->user_id) && (Auth::user()->role == "admin"))
                                                <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton-{{ $comment->id }}">
                                                    <li class="mb-2">
                                                        <a class="dropdown-item border-radius-md" href="#updateComment-{{ $comment->id }}" data-bs-toggle="modal"><span class="text-primary">Update</span></a>
                                                    </li>
                                                    <li class="mb-2">
                                                        <a class="dropdown-item border-radius-md" href="#changeCommentStatus-{{ $comment->id }}" data-bs-toggle="modal"><span class="text-warning">Block</span></a>
                                                    </li>
                                                    <li class="mb-1">
                                                        <a class="dropdown-item border-radius-md" href="#deleteComment-{{ $comment->id }}" data-bs-toggle="modal"><span class="text-danger">Delete</span></a>
                                                    </li>
                                                </ul>
                                            @elseif(Auth::user()->id == $comment->user_id)
                                                <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton-{{ $comment->id }}">
                                                    <li class="mb-2">
                                                        <a class="dropdown-item border-radius-md" href="#updateComment-{{ $comment->id }}" data-bs-toggle="modal"><span class="text-primary">Update</span></a>
                                                    </li>
                                                    <li class="mb-1">
                                                        <a class="dropdown-item border-radius-md" href="#deleteComment-{{ $comment->id }}" data-bs-toggle="modal"><span class="text-danger">Delete</span></a>
                                                    </li>
                                                </ul>
                                            @elseif((Auth::user()->id != $comment->user_id) && (Auth::user()->role == "admin"))
                                                <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton-{{ $comment->id }}">
                                                    <li class="mb-2">
                                                        <a class="dropdown-item border-radius-md" href="#changeCommentStatus-{{ $comment->id }}" data-bs-toggle="modal">
                                                            <span class="text-warning">Block</span>
                                                        </a>
                                                    </li>
                                                    <li class="mb-1">
                                                        <a class="dropdown-item border-radius-md" href="#deleteComment-{{ $comment->id }}" data-bs-toggle="modal">
                                                            <span class="text-danger">Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            @endif
                                        </div>

                                        @include('modals.network.updateComment')

                                        @include('modals.network.deleteComment')

                                        @include('modals.network.changeCommentStatus')
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        @if($comments->count() > 3)
                            <a href="#" id="showAllComments" class="purple-link" style="color:#6610f2;">Afficher tous les commentaires...</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right menu--->
        <div class="right">
            <!-- Messages -->
            <div class="messages">
                <div class="heading">
                    <h4>Explorer</h4>
                    <i class="uil uil-compass"></i>
                </div>

                <!-- Search bar -->
                <div class="search-bar">
                    <i class="uil uil-search"></i>
                    <input type="search" placeholder="Rechercher des profils" id="message-search">
                </div>
            </div>

            <!--- Friends request --->
            <div class="friend-requests">
                <h4>Suggestion d'amis</h4>
                @php
                $count = 0;
            @endphp

            @foreach($users as $user)
                @if($count < 6 && $user->id !== Auth::user()->id)
                    <div class="request">
                        <div class="info">
                            <div class="profile-photo">
                                <img src="{{ ($user->profile_photo_path !== null) ? Storage::url($user->profile_photo_path) : './images/user_circle.svg' }}">
                            </div>
                            <p> {{ $user->first_name }} {{ $user->last_name }}</p>

                        </div>
                        <div class="action">
                            <button class="btn btn-primary">
                                Accepter
                            </button>
                            <button class="btn">
                                Décliner
                            </button>
                        </div>
                    </div>
                    @php
                        $count++;
                    @endphp
                @endif
            @endforeach
                <div class="request">
                    <div class="info">
                        <div class="profile-photo">
                            <img src="./images/profile-17.jpg">
                        </div>
                        <div>
                            <h5>Megan Baldwin</h5>
                            <p class="text-muted">0 amis communs</p>
                        </div>
                    </div>
                    <div class="action">
                        <button class="btn btn-primary">
                            Accepter
                        </button>
                        <button class="btn">
                            Decliner
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--- Theme customization --->
    <div class="customize-theme">
        <div class="card">
            <h2>Customize your view</h2>
            <p class="text-muted">Manage your font size, color, and background</p>

            <!--- Font size --->
            <div class="font-size">
                <h4>Font Size</h4>
                <div>
                    <h6>Aa</h6>
                    <div class="choose-size">
                        <span class="font-size-1"></span>
                        <span class="font-size-2 active"></span>
                        <span class="font-size-3"></span>
                        <span class="font-size-4"></span>
                        <span class="font-size-5"></span>
                    </div>
                    <h3>Aa</h3>
                </div>
            </div>

            <!--- Primary color ---->
            <div class="color">
                <h4>Color</h4>
                <div class="choose-color">
                    <span class="color-1 active"></span>
                    <span class="color-2"></span>
                    <span class="color-3"></span>
                    <span class="color-4"></span>
                    <span class="color-5"></span>
                </div>
            </div>

            <!--- Backgound colors --->
            <div class="background">
                <h4>Background</h4>
                <div class="choose-bg">
                    <div class="bg-1 active">
                        <span></span>
                        <h5 for="bg-1">Light</h5>
                    </div>
                    <div class="bg-2">
                        <span></span>
                        <h5 for="bg-2">Dim</h5>
                    </div>
                    <div class="bg-3">
                        <span></span>
                        <h5 for="bg-3">Dark</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
