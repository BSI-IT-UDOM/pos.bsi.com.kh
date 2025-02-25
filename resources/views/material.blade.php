@extends('layouts.app-nav')
@section('content')
<div class="flex flex-col">
  <div class="bg-background flex flex-col items-center flex-grow px-4 md:px-0 mt-2">
    <div class="flex flex-col md:flex-row justify-between items-center w-full md:w-4/5">
      <a href="#" id="createMaterialButton" class="bg-primary text-primary-foreground py-1 px-8 rounded-lg md:mb-3 sm:mb-2">CREATE</a>
      <div class="relative flex w-full md:w-auto">
        <form id="searchForm" method="GET" class="w-full md:w-auto flex items-center">
            <input  id="searchInput" type="text" name="search" placeholder="Search..." class="border border-input rounded-full py-1 px-4 pl-10 w-full md:w-auto focus:outline-none focus:ring-2 focus:ring-primary"  />
            <button type="submit" class="bg-gray-200 rounded-full py-1 px-4 absolute right-0 top-0 mt-1 mr-2 flex items-center justify-center">
                <i class="fas fa-search text-gray-500"></i>
            </button>
        </form>
      </div>
    </div>
    <div class="w-full md:w-4/5 border-2 border-bsicolor p-2 font-times">
      <div class="overflow-x-auto">
        <h4 class="text-center font-bold pb-4 text-lg"><u>MATERIAL INFORMATION</u></h4>
        <table class="min-w-full bg-white border-collapse">
          <thead>
            <tr class="bg-primary text-primary-foreground text-lg">
              <th class="py-4 px-4 border border-white">NO.</th>
              <th class="py-4 px-4 border border-white">
                <a href="{{ url('/material?sortColumn=Material_Engname&sortOrder=' . (request('sortOrder') == 'asc' ? 'desc' : 'asc') . '&search=' . request('search')) }}">
                  NAME
                  <i class="fas fa-xs {{ request('sortColumn') == 'Material_Engname' ? (request('sortOrder') == 'asc' ? 'fa-sort-alpha-up' : 'fa-sort-alpha-down') : 'fa-sort-alpha-down' }}"></i>
                </a>
              </th>
              <th class="py-4 px-4 border border-white">
                <a href="{{ url('/material?sortColumn=category_name&sortOrder=' . (request('sortOrder') == 'asc' ? 'desc' : 'asc') . '&search=' . request('search')) }}">
                  CATEGORY
                  <i class="fas fa-xs {{ request('sortColumn') == 'category_name' ? (request('sortOrder') == 'asc' ? 'fa-sort-alpha-up' : 'fa-sort-alpha-down') : 'fa-sort-alpha-down' }}"></i>
                </a>
              </th>
              <th class="py-4 px-4 border border-white">IMAGE</th>
              <th class="py-4 px-4 border border-white">ACTION</th>
            </tr>
          </thead>
          <tbody id="inventoryTableBody">
            @foreach ($material as $data)
            <tr class="{{ $loop->index % 2 === 0 ? 'bg-zinc-200' : 'bg-zinc-300' }} text-base {{ $loop->first ? 'border-t-4' : '' }} text-center border-white">
              <td class="text-center py-3 px-4 border border-white">{{ $loop->iteration }}</td>
              <td class="text-center py-3 px-4 border border-white">{{ $data->Material_Engname .'    '. $data->Material_Khname?? 'null' }}</td>
              <td class="text-center py-3 px-4 border border-white">{{ $data->materialCategory->Material_Cate_Engname .'    '. $data->materialCategory->Material_Cate_Khname ?? 'null' }}</td>
              <td class="flex items-center justify-center py-3 px-4 border border-white">
                @if($data->image)
                    <img src="{{ asset('storage/' . $data->image) }}" alt="Material Image" class="h-10 w-12 rounded">
                @else
                    <span class="text-gray-500"></span>
                @endif
              </td>
              <td class="py-3 border border-white">
                <button class="relative bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white py-2 px-4 rounded-md focus:outline-none transition duration-150 ease-in-out group" onclick="openEditPopup({{ $data->Material_id }}, '{{ $data->Material_Khname }}','{{ $data->Material_Engname }}','{{ $data->materialCategory->Material_Cate_Khname ?? 'null'}}','{{ $data->image }}')">
                  <i class="fas fa-edit fa-xs"></i>
                  <span class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out">Edit</span>
                </button>
                <button class="relative bg-red-500 hover:bg-red-600 active:bg-red-700 text-white py-2 px-4 rounded-md focus:outline-none transition duration-150 ease-in-out group" onclick="confirmDelete({{ $data->Material_id }})">
                  <i class="fas fa-trash-alt fa-xs"></i>
                  <span class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out">Delete</span>
                </button>
                <button class="relative bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white py-2 px-4 rounded-md focus:outline-none transition duration-150 ease-in-out group"
                onclick="toggleActive(this, {{ $data->Material_id }})"
                onmouseover="setHover(this, true)"
                onmouseout="setHover(this, false)"
                style="background-color: {{ $data->status === 'Active' ? '#008000' : '#f00' }}; color: white;">
                <i class="fas {{ $data->status === 'Active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                <span class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 text-xs text-white bg-gray-600 px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                    {{ $data->status === 'Active' ? 'Active' : 'Inactive' }}
                </span>
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="mt-2">
          {{ $material->links() }} 
        </div>
      </div>
    </div>
  </div>
  @include('popups.create-material-popup')
  @include('popups.edit-material-popup')
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
  $('#searchInput').keypress(function() {
    var searchValue = $(this).val();
    $.ajax({
        url: '{{ route("material.search") }}',
        type: 'GET',
        data: { search: searchValue },
        success: function(response) {
          $('#inventoryTableBody').html(response.html);
        }
      });
});
function openEditPopup(Material_id, Material_Khname, Material_Engname, Material_Cate_Khname, image) {
  document.getElementById('editMaterial_id').value = Material_id;
  document.getElementById('editMaterial_Khname').value = Material_Khname;
  document.getElementById('editMaterial_Engname').value = Material_Engname;
  document.getElementById('editMaterial_Cate_Khname').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const uomName = selectedOption.getAttribute('data-uom-name');
  });
  const imagePreview = document.getElementById('imagePreview');
  if (image) {
    imagePreview.src = `/storage/${image}`;
    imagePreview.classList.remove('hidden');
  } else {
    imagePreview.src = '';
    imagePreview.classList.add('hidden');
  }
  document.getElementById('editMaterialForm').action = `/material_update/${Material_id}`;
  document.getElementById('editMaterialPopup').classList.remove('hidden');
}
const createButton = document.getElementById('createMaterialButton');
const popupForm = document.getElementById('createMaterialPopup');
createButton.addEventListener('click', function() {
  popupForm.classList.remove('hidden');
});

function confirmDelete(materialId) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = '/material/destroy/' + materialId;
    }
  });
}

function toggleActive(button, materialId) {
    const icon = button.querySelector('i');
    const currentStatus = icon.classList.contains('fa-toggle-on') ? 'Active' : 'Inactive';
    const newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';
    $.ajax({
        url: '/material/status/update',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            material_id: materialId,
            status: newStatus,
        },
        success: function() {
            if (newStatus === 'Active') {
                icon.classList.remove('fa-toggle-off');
                icon.classList.add('fa-toggle-on');
                button.style.backgroundColor = '#008000';
            } else {
                icon.classList.remove('fa-toggle-on');
                icon.classList.add('fa-toggle-off');
                button.style.backgroundColor = '#f00';
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an issue updating the status.',
            });
        }
    });
}
function setHover(button, isHover) {
    const icon = button.querySelector('i');
    const statusText = button.querySelector('span');
    if (isHover) {
        icon.style.color = '#fff';
        statusText.style.opacity = '1';
    } else {
        icon.style.color = '';
        statusText.style.opacity = '0';
    }
}
</script>
@endsection
