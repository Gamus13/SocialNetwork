<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function viewPost()
    {
        $posts = Post::where('status', '=', 'enabled')->orderByDesc('created_at')->paginate(10);
        $comments = Comment::where('status', '=', 'enabled')->orderByDesc('created_at')->paginate(10);
        $users = User::orderByDesc('created_at')->paginate(10);

        return response()->view('thor_network', compact('posts', 'comments', 'users'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::orderByDesc('created_at')->paginate(10);

        return response()->view('pages.post', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // Validation flieds
    //     if ($request->text_post == "") {
    //         $validator = Validator::make($request->all(), [
    //             'text_post' => 'nullable|string|max:255',
    //             'image_post' => 'required|image|max:2048|mimes:jpg,jpeg,png,svg',
    //             'user_id' => 'required|integer',
    //         ]);
    //     } elseif ($request->image_post == "") {
    //         $validator = Validator::make($request->all(), [
    //             'text_post' => 'required|string|max:255',
    //             'image_post' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,svg',
    //             'user_id' => 'required|integer',
    //         ]);
    //     } else {
    //         $validator = Validator::make($request->all(), [
    //             'text_post' => 'required|string|max:255',
    //             'image_post' => 'required|image|max:2048|mimes:jpg,jpeg,png,svg',
    //             'user_id' => 'required|integer',
    //         ]);
    //     }

    //     $validated = $validator->validated();

    //     // dd($validated);

    //     if ($request->hasFile('image_post')) {
    //         // Delete current image
    //         if (isset($request->image_post)) {
    //             Storage::disk('public')->delete($request->image_post);
    //         }

    //         // Store the new image in the public storage
    //         $filePath = Storage::disk('public')->put('images/posts', request()->file('image_post'));
    //         $validated['image_post'] = $filePath;
    //     }

    //     // Create post
    //     if (isset($validated['text_post'], $validated['image_post'])) {
    //         Post::create([
    //             'text_post' => $validated['text_post'],
    //             'image_post' => $validated['image_post'],
    //             'user_id' => $validated['user_id'],
    //         ]);
    //     } elseif (isset($validated['text_post'])) {
    //         Post::create([
    //             'text_post' => $validated['text_post'],
    //             'user_id' => $validated['user_id'],
    //         ]);
    //     } elseif (isset($validated['image_post'])) {
    //         Post::create([
    //             'image_post' => $validated['image_post'],
    //             'user_id' => $validated['user_id'],
    //         ]);
    //     }

    //     return redirect()->route('network');
    // }

    // public function store(Request $request)
    // {
    //     // Validation des champs
    //     if ($request->text_post == "") {
    //         $validator = Validator::make($request->all(), [
    //             'text_post' => 'nullable|string|max:255',
    //             'media_file' => 'required|file|max:2048|mimes:jpg,jpeg,png,svg,mp4,mov,avi,mkv',
    //             'user_id' => 'required|integer',
    //         ]);
    //     } elseif ($request->media_file == "") {
    //         $validator = Validator::make($request->all(), [
    //             'text_post' => 'required|string|max:255',
    //             'media_file' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,svg,mp4,mov,avi,mkv',
    //             'user_id' => 'required|integer',
    //         ]);
    //     } else {
    //         $validator = Validator::make($request->all(), [
    //             'text_post' => 'required|string|max:255',
    //             'media_file' => 'required|file|max:2048|mimes:jpg,jpeg,png,svg,mp4,mov,avi,mkv',
    //             'user_id' => 'required|integer',
    //         ]);
    //     }

    //     $validated = $validator->validated();

    //     if ($request->hasFile('media_file')) {
    //         // Supprimer le fichier actuel
    //         if (isset($request->image_post)) {
    //             Storage::disk('public')->delete($request->image_post);
    //         }

    //         // Stocker le nouveau fichier dans le stockage public
    //         $filePath = Storage::disk('public')->put('files/posts', request()->file('media_file'));
    //         $validated['media_file'] = $filePath;
    //     }

    //     // Créer le post
    //     Post::create([
    //         'text_post' => $validated['text_post'] ?? null,
    //         'media_file' => $validated['media_file'] ?? null,
    //         'user_id' => $validated['user_id'],
    //     ]);

    //     return redirect()->route('network');
    // }

    public function store(Request $request)
    {
        // Validation des champs
        $validator = Validator::make($request->all(), [
            'text_post' => 'nullable|string|max:255',
            'media_file' => 'required|file|max:2048|mimes:jpg,jpeg,png,svg,mp4,mov,avi,mkv',
            'user_id' => 'required|integer',
        ]);

        $validated = $validator->validated();

        if ($request->hasFile('media_file')) {
            $mediaFile = $request->file('media_file');
            $mediaType = $mediaFile->getClientOriginalExtension();

            // Déterminer le dossier de destination en fonction du type de média
            $destinationFolder = $mediaType === 'mp4' || $mediaType === 'mov' ? 'files/videos' : 'files/images';

            // Stocker le fichier dans le dossier approprié du stockage public
            $filePath = Storage::disk('public')->put($destinationFolder, $mediaFile);
            $validated['media_file'] = $filePath;
        }

        // Créer le post
        Post::create([
            'text_post' => $validated['text_post'] ?? null,
            'media_file' => $validated['media_file'] ?? null,
            'user_id' => $validated['user_id'],
        ]);

        return redirect()->route('network');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->view('posts.show', [
            'post' => Post::findOrFail($post),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Validation flieds
        if ($request->text_post == "") {
            $validator = Validator::make($request->all(), [
                'text_post' => 'nullable|string|max:255',
                'image_post' => 'required|image|max:2048|mimes:jpg,jpeg,png,svg',
                'user_id' => 'required|integer',
            ]);
        } elseif ($request->image_post == "") {
            $validator = Validator::make($request->all(), [
                'text_post' => 'required|string|max:255',
                'image_post' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,svg',
                'user_id' => 'required|integer',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'text_post' => 'required|string|max:255',
                'image_post' => 'required|image|max:2048|mimes:jpg,jpeg,png,svg',
                'user_id' => 'required|integer',
            ]);
        }

        $validated = $validator->validated();

        if ($request->hasFile('image_post')) {
            // Delete current image
            if (isset($request->image_post)) {
                Storage::disk('public')->delete($request->image_post);
            }

            // Store the new image in the public storage
            $filePath = Storage::disk('public')->put('images/posts', request()->file('image_post'));
            $validated['image_post'] = $filePath;
        }

        // Update post
        $post->update($validated);

        return redirect()->route('network');
    }

    /**
     * Update the specified resource in storage.
     */
    public function changeStatus(Post $post)
    {
        if ( $post->status == 'enabled') {
             $post->status = 'disabled';
        } elseif ( $post->status == 'disabled') {
             $post->status = 'enabled';
        }

        // Change post status
        $post->update(['status' => $post->status]);

        return redirect()->route('network');
    }

    /**
     * Update the specified resource in storage.
     */
    public function changeStatus1(Post $post)
    {
        if ( $post->status == 'enabled') {
             $post->status = 'disabled';
        } elseif ( $post->status == 'disabled') {
             $post->status = 'enabled';
        }

        // Change post status
        $post->update(['status' => $post->status]);

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Delete image
        if (isset($post->image_post)) {
            Storage::disk('public')->delete($post->image_post);
        }

        // We delete post
        $post->delete();

        return redirect()->route('network');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy1(Post $post)
    {
        // Delete image
        if (isset($post->image_post)) {
            Storage::disk('public')->delete($post->image_post);
        }

        // We delete post
        $post->delete();

        return redirect()->route('posts.index');
    }
}
