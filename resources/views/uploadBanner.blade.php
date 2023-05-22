<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{__('Homepage.UploadBanner')}}
        </h2>
    </x-slot>

    <div class="text-white flex justify-center align-middle">
        <h1 class="text-2xl">{{__('Homepage.UploadNewBannerPage')}}</h1>
    </div>

    <div class="text-white flex justify-center align-middle">
        <form action="{{route("uploadBanner")}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="image" accept="image/jpeg">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold w-fit py-2 px-4 rounded" type="submit">Upload</button>
        </form>
    </div>
</x-app-layout>
