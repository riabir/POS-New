    <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Employee') }}
        </h2>
    </x-slot>
</form> 

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                   <div class="container">

  
    <form method="post" action="{{route('employees.store')}}">
        @csrf
  <label for="first_name">First name:</label><br>
  <input type="text" id="first_name" name="first_name" value=""><br>

  <label for="last_name">Last name:</label><br>
  <input type="text" id="last_name" name="last_name" value=""><br>

  <label for="email">Email Address:</label><br>
 <input type="text" id="email" name="email" value=""><br>

  <label for="phone">Phone Number</label><br>
  <input type="text" id="phone" name="phone" value=""><br>

  <label for="address">Address</label><br>
 <input type="text" id="address" name="address" value=""><br>

  <input type="submit" class="p-3 bg-red-800 text-white rounded-xl" value="Submit">
</form> 

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

       

