 <div id="modalLogout" class="fixed inset-0 bg-opacity-50 flex items-center justify-center p-4 hidden">
     <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
         <div class="flex justify-between items-center p-4 ">
             <h2 class="text-xl font-semibold">Logout</h2>
             <button id="closeModalLogout" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                     </path>
                 </svg>
             </button>
         </div>
         <div class="p-2 space-y-4 ">
             <h1 class="font-bold flex items-center justify-center">ARE YOU SURE WANT TO LOGOUT ?</h1>
             <div class="p-1 flex items-center justify-center">
                 <form action="{{ route('logout') }}" method="POST">
                    @csrf
                     <button class="px-4 py-2 bg-orange-400 text-white rounded-md hover:bg-orange-500 cursor-pointer"
                         type="submit">yes</button>
                 </form>
             </div>
             </form>
         </div>

     </div>
 </div>
