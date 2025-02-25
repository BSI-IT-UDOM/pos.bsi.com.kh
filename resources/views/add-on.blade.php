@extends('layouts.setting')
@section('content')
<div class="flex flex-col">
  <div class="bg-background flex flex-col items-center flex-grow px-4 md:px-0 mt-2">
    <div class="flex flex-col md:flex-row justify-between items-center w-full md:w-4/5">
      <a href="#" id="createButton" class="bg-green-500 text-white font-bold py-1 px-4 md:py-2 md:px-6 rounded flex items-center text-sm md:text-base">CREATE</a>
      <div class="relative flex w-full md:w-auto">
        <form id="searchForm" method="GET" class="w-full md:w-auto flex items-center">
          <input id="searchInput" type="text" placeholder="Search..." class="border border-input rounded-full py-1 px-4 pl-10 w-full md:w-auto focus:outline-none focus:ring-2 focus:ring-primary"  />
          <button type="submit" class="bg-gray-200 rounded-full py-1 px-4 absolute right-0 top-0 mt-1 mr-2 flex items-center justify-center">
            <i class="fas fa-search text-gray-500"></i>
          </button>
        </form>
      </div>
    </div>
    <div class="w-full md:w-4/5 border-2 border-bsicolor p-2 font-times">
      <div class="overflow-x-auto w-full">
        <h4 class="text-center font-bold pb-4 text-lg"><u>ADD-ON INFORMATION</u></h4>
        <table class="min-w-full bg-white shadow-md rounded-lg">
          <thead class="bg-gray-200">
            <tr class="bg-primary text-primary-foreground text-lg">
              <th class="text-center py-2 px-3 md:py-3 md:px-6 border-b text-left">NO.</th>
              <th class="text-center py-2 px-3 md:py-3 md:px-6 border-b text-left">NAME</th>
              <th class="text-center py-2 px-3 md:py-3 md:px-6 border-b text-left">PERCENTAGE</th>
              <th class="text-center py-2 px-3 md:py-3 md:px-6 border-b text-left">QTY</th>
              <th class="text-center py-2 px-3 md:py-3 md:px-6 border-b text-left">ACTION</th>
            </tr>
          </thead>
          <tbody id="inventoryTableBody">
            @foreach ($Addons as $data)
            <tr class="{{ $loop->index % 2 === 0 ? 'bg-zinc-200' : 'bg-zinc-300' }} text-base {{ $loop->first ? 'border-t-4' : '' }} text-center border-white">
              <td class="text-center py-2 px-3 md:py-3 md:px-6 border-b">{{ $loop->iteration }}</td>
              <td class="text-center py-2 px-3 md:py-3 md:px-6 border-b">{{ $data->Addons_name ?? 'null' }}</td>
              <td class="text-center py-2 px-3 md:py-3 md:px-6 border-b">{{ $data->Percentage * 100 . '%' ?? 'null' }}</td>
              <td class="text-center py-2 px-3 md:py-3 md:px-6 border-b">{{ $data->Qty . '       ' . $data->uom->UOM_abb ?? 'null' }}</td>
              <td class="py-3 border border-white">
                <button class="relative bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white py-2 px-4 rounded-md focus:outline-none transition duration-150 ease-in-out group" onclick="openEditPopup({{ $data->Addons_id }}, '{{ $data->Addons_name ?? 'null' }}','{{ $data->Percentage ?? 'null'}}','{{ $data->Qty ?? 'null'}}','{{ $data->uom->UOM_name ?? 'null'}}')">
                  <i class="fas fa-edit fa-xs"></i>
                  <span class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out">Edit</span>
                </button>
                <button class="relative bg-red-500 hover:bg-red-600 active:bg-red-700 text-white py-2 px-4 rounded-md focus:outline-none transition duration-150 ease-in-out group" onclick="confirmDelete({{ $data->Addons_id }})">
                  <i class="fas fa-trash-alt fa-xs"></i>
                  <span class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-1 px-2 py-1 text-xs text-white bg-gray-800 rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out">Delete</span>
                </button>
                <button class="relative bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white py-2 px-4 rounded-md focus:outline-none transition duration-150 ease-in-out group"
                  onclick="toggleActive(this, {{ $data->Addons_id }})"
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
        <div class="mt-4">
          {{ $Addons->links() }}
        </div>
      </div>
    </div>
  </div>
  @include('popups.create-addon-popup')
  @include('popups.edit-addon-popup')
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/closePop.js') }}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
  $('#searchForm').on('submit', function(event) {
    event.preventDefault();
    let searchQuery = $('#searchInput').val();

    $.ajax({
      url: '{{ route("add-on.search") }}',
      type: 'GET',
      data: { search: searchQuery },
      success: function(response) {
        $('#inventoryTableBody').html(response.html);
      }
    });
  });

  function confirmDelete(Addons_id) {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Yes, delete it!"
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = `/add-on/destroy/${Addons_id}`;
      }
    });
  }

  function openEditPopup(Addons_id, Addons_name, Percentage, Qty, uom) {
    document.getElementById('editAddons_id').value = Addons_id;
    document.getElementById('editAddons_name').value = Addons_name;
    document.getElementById('editPercentage').value = Percentage;
    document.getElementById('editQty').value = Qty;

    document.getElementById('editProductPopup').action = `/add-on/${Addons_id}`;
    document.getElementById('editPopup').classList.remove('hidden');
  }

  function toggleActive(button, materialId) {
    const icon = button.querySelector('i');
    const currentStatus = icon.classList.contains('fa-toggle-on') ? 'Active' : 'Inactive';
    const newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';

    fetch(`/add-on/${materialId}/toggle-status`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        if (newStatus === 'Active') {
          icon.classList.replace('fa-toggle-off', 'fa-toggle-on');
          button.style.backgroundColor = '#008000'; 
        } else {
          icon.classList.replace('fa-toggle-on', 'fa-toggle-off');
          button.style.backgroundColor = '#f00'; 
        }
      } else {
        alert("Unable to change status.");
      }
    })
    .catch(error => console.error('Error:', error));
  }

  function setHover(button, isHover) {
    const icon = button.querySelector('i');
    const statusText = button.querySelector('span');
    
    if (icon.classList.contains('fa-toggle-on')) {
      button.style.backgroundColor = isHover ? '#006400' : '#008000';
      statusText.textContent = 'Active';
    } else {
      button.style.backgroundColor = isHover ? '#a11' : '#f00';
      statusText.textContent = 'Inactive';
    }
  }
</script>
@endsection
