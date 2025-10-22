@extends('layout.user')

{{-- Mulai bagian konten yang akan dimasukkan ke dalam {{ $slot }} --}}
@section('content')

<div class="container mx-auto px-4 py-2">
    <div class="max-w-4xl mx-auto bg-gray-100 overflow-hidden">

        {{-- Konten Artikel --}}
        <div class="px-4">
            {{-- PERUBAHAN 1: Menambahkan font-serif pada judul --}}
            <h1 class="text-3xl font-bold text-gray-900 mb-4 font-serif">{{ $article->title }}</h1>
            <p class="text-sm text-gray-500 mb-3">Dipublikasikan pada: {{ $article->created_at->format('d M Y') }}</p>
        </div>


        {{-- Menampilkan Gambar atau Video --}}
        @if ($article->image)
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-96 object-cover my-4">
        @elseif ($article->video)
            <div class="aspect-video w-full my-4">
                {{-- <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ \App\Helpers\YoutubeHelper::getYoutubeId($article->video) }}" frameborder="0" allowfullscreen></iframe> --}}
            </div>
        @else
            <div class="w-full h-96 bg-gray-200 my-4"></div>
        @endif
        
        {{-- PERUBAHAN 2: Menambahkan font-serif dan styling prose --}}
        <div class="prose prose-lg prose-p:leading-relaxed max-w-none text-gray-800 py-3 px-3 font-serif">
            {!! nl2br(e($article->content)) !!}
        </div>


         {{-- Tombol Kembali --}}
        <div class="my-5 ">
            <a href="{{ route('edukasi') }}" 
               class="inline-block rounded bg-gray-600 px-5 py-2.5 text-white transition-all duration-300 hover:bg-gray-800">
                Kembali
            </a>
        </div>
    </div>


   
</div>

@endsection