<!-- Create post modal -->
<div id="createPost" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" action="{{ route('posts.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('post')

                    <div>
                        <textarea name="text_post" id="text_post" class="form-control" placeholder="quel est votre publication?" autofocus></textarea>
                        @error('text_post')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div>
                        <label class="block mt-2" for="image_post">Importer une image
                            <span class="sr-only">Add image</span>
                            <input type="file" id="image_post" name="image_post" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-violet-50 file:text-violet-700
                                hover:file:bg-violet-100
                            "/>
                        </label>
                        <div class="shrink-0 my-2">
                            <img id="image_preview" class="h-64 w-128 object-cover rounded-md" src="{{ isset($post) ? Storage::url($post->image_post) : '' }}" alt="Aperçu de l'image" />
                        </div>
                        @error('image_post')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    <div>
                        <label class="block mt-2" for="media_file">Importer un fichier
                            <span class="sr-only">Add file</span>
                            <input type="file" id="media_file" name="media_file" accept="image/*, video/*" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-violet-50 file:text-violet-700
                                hover:file:bg-violet-100
                            "/>
                        </label>
                        <div class="shrink-0 my-2">
                            <div id="media_preview_container"></div>
                        </div>
                        @error('media_file')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <script>
                        document.getElementById('media_file').addEventListener('change', function(e) {
                            var file = e.target.files[0];
                            var mediaPreviewContainer = document.getElementById('media_preview_container');
                            mediaPreviewContainer.innerHTML = '';

                            if (file.type.startsWith('image/')) {
                                var img = document.createElement('img');
                                img.src = URL.createObjectURL(file);
                                img.alt = 'Aperçu de l\'image';
                                img.className = 'h-64 w-128 object-cover rounded-md';
                                mediaPreviewContainer.appendChild(img);
                            } else if (file.type.startsWith('video/')) {
                                var video = document.createElement('video');
                                video.src = URL.createObjectURL(file);
                                video.type = file.type;
                                video.className = 'h-64 w-128 object-cover rounded-md';
                                video.controls = true;
                                mediaPreviewContainer.appendChild(video);
                            }
                        });
                    </script>

                    <div>
                        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Poster</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
