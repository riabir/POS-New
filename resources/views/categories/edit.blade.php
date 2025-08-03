<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Categories Update') }}
        </h2>
    </x-slot>
    </form>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">


                        <form method="post" action="{{route('categories.update', $categorie->id)}}">
                            @csrf
                            @method('put')

                                                  
                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                     rows="3" value ="{{ $categorie->name }}"> 
                                    
                            </div>                           

                            <input type="submit" class="btn btn-primary" value="Submit">                           
                        </form>

                    </div>
                </div>
            </div>
        </div>
</x-app-layout>