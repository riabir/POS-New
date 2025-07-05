<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Categories') }}
        </h2>
    </x-slot>

    </form>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">                       

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#registerModal">
                            Categories
                        </button>

                        <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="registerModalLabel">Add Categories</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- categories Form -->
                                        <form id="categorieForm" method="post" action="{{route('categories.store')}}">
                                            @csrf                                          

                                            <!-- Categories Name -->
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Categories Name</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    rows="3"></input>
                                            </div>                                        

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <button type="button" class="btn btn-secondary"
                                                    onclick="clearForm()">Clear</button>


                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br><br><br>


                        <h1>Categories List</h1>
                        <br>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <tr>

                                    <th>No</th>                                   
                                    <th>Categorie</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($categories as $categorie)
                                    <tr>
                                        <td>{{ $categorie->id }} </td> 
                                        <td>{{ $categorie->name }} </td>                                     

                                        <td>
                                            <a href="{{route('categories.edit', $categorie->id)}}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                        </td>
                                        <td>
                                            <form method="POST" action="{{route('categories.destroy', $categorie->id)}}">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('are you sure')"
                                                    type="submit">Delete</button>
                                            </form>
                                        </td>

                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function clearForm() {
            document.getElementById("categorieForm").reset();
        }
    </script>
</x-app-layout>