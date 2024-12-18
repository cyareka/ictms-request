<x-app-layout class="shadow-xs">
    <div class="py-12">
        <div>
            <div>
                <x-vehicleedit :requestData="$requestData" :passengers="$passengers" :availableDrivers="$availableDrivers"  :availableVehicles="$availableVehicles"/>
            </div>
        </div>
    </div>
</x-app-layout>
