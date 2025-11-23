@extends('layouts.app')

@section('title', 'AisNews | Baca Berita Online Terbaru')

@section('content')

<!-- Swiper -->
<div class="swiper mySwiper mt-9">
    <div class="swiper-wrapper">
        @foreach ($banners as $banner)
            <div class="swiper-slide">
                <a href="{{ route('news.show', $banner->news->slug) }}" class="block">
                    <div class="relative flex flex-col gap-1 justify-end p-3 h-72 rounded-xl bg-cover bg-center overflow-hidden"
                        style="background-image: url('{{ asset('storage/' . $banner->news->thumbnail) }}')">
                        <div class="absolute inset-x-0 bottom-0 h-full bg-gradient-to-t 
                        from-[rgba(0,0,0,0.4)] to-[rgba(0,0,0,0)] rounded-b-xl"></div>

                        <div class="relative z-10 mb-3 pl-3">
                            <div class="bg-primary text-white text-xs rounded-lg w-fit px-3 py-1 font-normal mt-3">
                                {{ $banner->news->newsCategory->title }}
                            </div>

                            <p class="text-3xl font-semibold text-white mt-1">
                                {{ $banner->news->title }}
                            </p>

                            <div class="flex items-center gap-1 mt-1">
                                <img src="{{ asset('storage/' . $banner->news->author->avatar) }}" 
                                     alt="" class="w-5 h-5 rounded-full">
                                <p class="text-white text-xs">{{ $banner->news->author->name }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<!-- Berita Unggulan -->
<div class="flex flex-col px-14 mt-10">
    <div class="flex flex-col md:flex-row justify-between items-center w-full mb-6">
        <div class="font-bold text-2xl text-center md:text-left">
            <p>Berita Unggulan</p>
            <p>Untuk Kamu</p>
        </div>
        <a href="{{ route('news.index') }}"
           class="bg-primary px-5 py-2 rounded-full text-white font-semibold mt-4 md:mt-0 h-fit">
            Lihat Semua
        </a>
    </div>

    <div class="grid sm:grid-cols-1 gap-5 lg:grid-cols-4">
        @foreach ($featureds as $featured)
            <a href="{{ route('news.show', $featured->slug) }}">
                <div class="border border-slate-200 p-3 rounded-xl hover:border-primary transition">
                    <div class="bg-primary text-white rounded-full w-fit px-5 py-1 font-normal ml-2 mt-2 text-sm absolute">
                        {{ $featured->newsCategory->title }}
                    </div>

                    <img src="{{ asset('storage/' . $featured->thumbnail) }}" 
                         class="w-full rounded-xl mb-3" style="height:200px; object-fit:cover;">

                    <p class="font-bold text-base mb-1">{{ $featured->title }}</p>
                    <p class="text-slate-400">
                        {{ \Carbon\Carbon::parse($featured->created_at)->format('d F Y') }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>
</div>

<!-- Berita Terbaru -->
<div class="flex flex-col px-4 md:px-10 lg:px-14 mt-10">
    <div class="mb-6">
        <p class="font-bold text-2xl">Berita Terbaru</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Berita Utama -->
        <div class="relative col-span-7 border border-slate-200 p-3 rounded-xl hover:border-primary">
            <a href="{{ route('news.show', $news[0]->slug) }}">
                <div class="bg-primary text-white rounded-full w-fit px-4 py-1 font-normal ml-5 mt-5 absolute">
                    {{ $news[0]->newsCategory->title }}
                </div>

                <img src="{{ asset('storage/' . $news[0]->thumbnail) }}" class="rounded-2xl">

                <p class="font-bold text-xl mt-3">{{ $news[0]->title }}</p>
                <p class="text-slate-400 mt-1">{!! \Str::limit($news[0]->content, 100) !!}</p>
                <p class="text-slate-400 mt-1">
                    {{ \Carbon\Carbon::parse($news[0]->created_at)->format('d F Y') }}
                </p>
            </a>
        </div>

        <!-- List Berita Lain -->
        @foreach ($news->skip(1) as $new)
        <a href="{{ route('news.show', $new->slug) }}"
           class="relative col-span-5 flex flex-col md:flex-row gap-3 border border-slate-200 p-3 rounded-xl hover:border-primary">

            <div class="bg-primary text-white rounded-full w-fit px-4 py-1 font-normal ml-2 mt-2 absolute text-sm">
                {{ $new->newsCategory->title }}
            </div>

            <img src="{{ asset('storage/' . $new->thumbnail) }}" 
                 class="rounded-xl w-full md:max-h-48" style="width:250px; object-fit:cover;">

            <div>
                <p class="font-semibold text-lg">{{ $new->title }}</p>
                <p class="text-slate-400 mt-3 text-sm">
                    {{ \Str::limit($new->content, 100) }}
                </p>
            </div>

        </a>
        @endforeach

    </div>
</div>

<!-- Author -->
<div class="flex flex-col px-4 md:px-10 lg:px-14 mt-10">
    <div class="flex flex-col md:flex-row justify-between items-center w-full mb-6">
        <div class="font-bold text-2xl">
            <p>Kenali Author</p>
            <p>Terbaik Dari Kami</p>
        </div>

        <a href="/admin/register" class="bg-primary px-5 py-2 rounded-full text-white font-semibold">
            Gabung Menjadi Author
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
        @foreach ($authors as $author)
        <a href="{{ route('author.show', $author->username) }}">
            <div class="flex flex-col items-center border border-slate-200 px-4 py-8 rounded-2xl hover:border-primary">
                <img src="{{ asset('storage/' . $author->avatar) }}" 
                     class="rounded-full w-24 h-24 object-cover">

                <p class="font-bold text-xl mt-4">{{ $author->user->name }}</p>
                <p class="text-slate-400">{{ $author->news->count() }} Berita</p>
            </div>
        </a>
        @endforeach
    </div>
</div>

<!-- Pilihan Author -->
<div class="flex flex-col px-14 mt-10 mb-10">
    <div class="mb-6">
        <p class="font-bold text-2xl">Pilihan Author</p>
    </div>

    <div class="grid sm:grid-cols-1 gap-5 lg:grid-cols-4">
        @foreach ($news as $choice)
        <a href="{{ route('news.show', $choice->slug) }}">
            <div class="border border-slate-200 p-3 rounded-xl hover:border-primary transition">
                <div class="bg-primary text-white rounded-full w-fit px-5 py-1 font-normal ml-2 mt-2 text-sm absolute">
                    {{ $choice->newsCategory->title }}
                </div>

                <img src="{{ asset('storage/' . $choice->thumbnail) }}" 
                     class="w-full rounded-xl mb-3" style="height:200px; object-fit:cover;">

                <p class="font-bold text-base mb-1">{{ $choice->title }}</p>
                <p class="text-slate-400">
                    {{ \Carbon\Carbon::parse($choice->created_at)->format('d F Y') }}
                </p>
            </div>
        </a>
        @endforeach
    </div>
</div>

@endsection
