<style>
    .pagination_rounded, .pagination_square {
        display: block;
        margin-top: 15px;
        text-align: center; /* Center the pagination buttons */
        width: 100%; /* Ensure the container takes the full width */
    }

    .pagination_rounded ul {
        margin: 0;
        padding: 0;
        list-style: none;
        display: inline-block; /* Make the list inline-block to center it */
    }

    .pagination_rounded ul li {
        display: inline; /* Display list items inline */
        margin-left: 20px;
    }

    .pagination_rounded ul li a {
        float: none; /* Remove float */
        display: inline-block; /* Ensure links are inline-block */
        color: #000000;
        border-radius: 50%;
        line-height: 30px;
        height: 30px;
        width: 30px;
        text-align: center;
        margin-bottom: 40px;
        border: 1px solid #e0e0e0;
    }

    .pagination_rounded ul li a:hover {
        background: #4285f4;
        color: #fff;
        border: 1px solid #4285f4;
    }

    .pagination_rounded .prev, .pagination_rounded .next {
        border-radius: 35px;
        width: 90px;
        height: 34px;
        line-height: 34px;
    }


    a:link {
        text-decoration: none;
    }


    .visible-xs {
        display: none!important;
    }
</style>

<div class="requests">
    <div class="filter">
        <div class="row height d-flex justify-content-left align-items-left">
            <div class="col-md-6">
                <div class="form">
                    <i class="fa fa-search"></i>
                    <input type="text" id="search-input" class="form-control form-input" placeholder="Search">
                </div>
            </div>
        </div>
        <div class="tableactions">
            <div id="divide">
                <div class="dropdown" style="float:right;">
                    <button class="dropbtn"><i class="bi bi-filter"></i></button>
                    <form id="filterForm" method="GET" action="{{ route('fetchSortedLogRequests') }}">
                        <div class="dropdown-content">
                            <p id="filterlabel">Filter By</p>
                            <hr>
                            <p>Conference Room</p>
                            <a>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="conference_room" value="Maagap" id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Maagap
                                    </label>
                                </div>
                            </a>
                            <a>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="conference_room" value="Magiting" id="flexRadioDefault2">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Magiting
                                    </label>
                                </div>
                            </a>
                            <p>Status</p>
                            <a>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status_pairs[]" value="Not Approved,-" id="flexCheckDefault1">
                                    <label class="form-check-label" for="flexCheckDefault1">
                                        Not Approved
                                    </label>
                                </div>
                            </a>
                            <a>
                                <div class="form-check" id="margincheck">
                                    <input class="form-check-input" type="checkbox" name="status_pairs[]" value="Approved,Finished" id="flexCheckDefault2">
                                    <label class="form-check-label" for="flexCheckDefault2">
                                        Approved and Finished
                                    </label>
                                </div>
                            </a>
                            <a>
                                <div class="form-check" id="margincheck">
                                    <input class="form-check-input" type="checkbox" name="status_pairs[]" value="Approved,Cancelled" id="flexCheckDefault3">
                                    <label class="form-check-label" for="flexCheckDefault3">
                                        Approved and Cancelled
                                    </label>
                                </div>
                            </a>
                            <hr>
                            <div class="buttons">
                                <button class="cancelbtn" type="button" onclick="resetFilters()">Remove</button>
                                <button class="applybtn" type="submit">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="tabview">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">
                    <a href="#" id="sort-date-requested" data-order="desc">
                        Date/Time Requested
                    </a>
                </th>
                <th scope="col">Conference Room</th>
                <th scope="col">Requesting Office</th>
                <th scope="col">Date Needed</th>
                <th scope="col">Time Needed</th>
                <th scope="col">Form Status</th>
                <th scope="col">Event Status</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody id="requests-tbody">
            <!-- Data will be populated here by JavaScript -->
            </tbody>
        </table>
        <div class="pagination_rounded">
            <ul id="pagination-list">

            </ul>
        </div>
    </div>
</div>
<div class="end"></div>

<script>
    let currentPage = 1;
    const itemsPerPage = 10; // Set items per page to 5
    let currentOrder = 'desc'; // Default order
    let searchQuery = ''; // Initialize searchQuery

    document.addEventListener('DOMContentLoaded', function () {
        fetchSortedData(currentOrder, currentPage, searchQuery); // Fetch data on page load
    });

    // Sort by Date Requested
    document.getElementById('sort-date-requested').addEventListener('click', function (e) {
        e.preventDefault();
        currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
        this.setAttribute('data-order', currentOrder);
        fetchSortedData(currentOrder, 1, searchQuery); // Reset to page 1 when sorting changes
    });

    // Fetch sorted, paginated, and filtered data
    function fetchSortedData(order = 'desc', page = 1, search = '', filters = {}) {
        const params = new URLSearchParams({
            sort: 'created_at',  // Sorting by 'created_at' column by default
            order: order,        // Ascending or descending order
            page: page,          // Current page number
            per_page: itemsPerPage, // Number of items per page
            search: search,      // Search term
            ...filters           // Spread the filters object into URL parameters
        });

        // Handle status_pairs array
        const statusPairs = ['Approved,Cancelled', 'Approved,Finished', 'Not Approved,-'];
        statusPairs.forEach((pair, index) => {
            params.append(`status_pairs[${index}]`, pair);
        });

        fetch(`/fetchSortedLogRequests?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                updateTable(data.data);    // Populate the table with returned data
                updatePagination(data.pagination);  // Handle pagination
            })
            .catch(error => console.error('Error fetching sorted data:', error));
    }
    
    // Update table rows
    function updateTable(data) {
        const tbody = document.getElementById('requests-tbody');
        tbody.innerHTML = ''; // Clear previous data

        if (data && data.length > 0) {
            data.forEach(request => {
                const row = `
                <tr>
                    <th scope="row">${request.CRequestID || 'N/A'}</th>
                    <td>${request.created_at ? new Date(request.created_at).toLocaleDateString() + ' ' + new Date(request.created_at).toLocaleTimeString() : 'N/A'}</td>
                    <td>${request.conference_room ? request.conference_room.CRoomName : 'N/A'}</td>
                    <td>${request.office?.OfficeName || 'N/A'}</td>
                    <td>${request.date_start || 'N/A'}</td>
                    <td>${request.time_start || 'N/A'}</td>
                    <td><span class="${request.FormStatus.toLowerCase()}">${request.FormStatus || 'N/A'}</span></td>
                    <td>${request.EventStatus || 'N/A'}</td>
                    <td>
                        <a href="/conferencerequest/${request.CRequestID}/log"><i class="bi bi-person-vcard"></i></a>
                    </td>
                </tr>`;
                tbody.insertAdjacentHTML('beforeend', row); // Add rows dynamically
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="9">No requests found.</td></tr>'; // Show if no results found
        }
    }

    // Update pagination links
    function updatePagination(pagination) {
    const paginationList = document.getElementById('pagination-list');
    paginationList.innerHTML = ''; // Clear current pagination

    const { total, current_page, last_page } = pagination;
    currentPage = current_page;

    // Previous button
    const prevPageItem = document.createElement('li');
    const prevPageLink = document.createElement('a');
    prevPageLink.href = '#';
    prevPageLink.classList.add('prev');
    prevPageLink.innerHTML = `<i class="fa fa-angle-left" aria-hidden="true"></i> Prev`;
    prevPageLink.addEventListener('click', function (e) {
        e.preventDefault();
        if (currentPage > 1) {
            fetchSortedData(currentOrder, currentPage - 1, searchQuery);
        }
    });
    prevPageItem.appendChild(prevPageLink);
    paginationList.appendChild(prevPageItem);

    // Page numbers
    for (let i = 1; i <= last_page; i++) {
        if (i === current_page || 
            i === current_page - 1 || 
            i === current_page - 2 || 
            i === current_page + 1 || 
            i === current_page + 2) {
            
            const pageItem = createPaginationItem(i, i);
            if (i === current_page) {
                pageItem.classList.add('active');
                const pageLink = pageItem.querySelector('a');
                pageLink.style.color = 'white';
                pageLink.style.backgroundColor = '#4285f4';
            }
            paginationList.appendChild(pageItem);
        }
    }

    // Next button
    const nextPageItem = document.createElement('li');
    const nextPageLink = document.createElement('a');
    nextPageLink.href = '#';
    nextPageLink.classList.add('next');
    nextPageLink.innerHTML = `Next <i class="fa fa-angle-right" aria-hidden="true"></i>`;
    nextPageLink.addEventListener('click', function (e) {
        e.preventDefault();
        if (currentPage < last_page) {
            fetchSortedData(currentOrder, currentPage + 1, searchQuery);
        }
    });
    nextPageItem.appendChild(nextPageLink);
    paginationList.appendChild(nextPageItem);
}

    // Create pagination item
    function createPaginationItem(text, page) {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = '#';
        a.textContent = text;

        a.addEventListener('click', (e) => {
            e.preventDefault();
            fetchSortedData(currentOrder, page, searchQuery);
        });

        li.appendChild(a);
        return li;
    }

    // Handle search input
    document.getElementById('search-input').addEventListener('input', function () {
        searchQuery = this.value.trim();
        fetchSortedData(currentOrder, 1, searchQuery); // Reset to page 1 when searching
    });

    // Handle filter form submission
    document.getElementById('filterForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const filters = {};

        // Get the selected conference room
        const conferenceRoom = document.querySelector('input[name="conference_room"]:checked');
        if (conferenceRoom) {
            filters.conference_room = conferenceRoom.value;
        }

        // Get the selected status pairs
        const statusPairs = Array.from(document.querySelectorAll('input[name="status_pairs[]"]:checked')).map(input => input.value);
        if (statusPairs.length > 0) {
            filters.status_pairs = statusPairs;
        }

        // Fetch data with the selected filters
        fetchSortedData(currentOrder, 1, searchQuery, filters); // Reset to page 1 and fetch filtered data
    });

    // Reset filters
    function resetFilters() {
        document.getElementById('filterForm').reset();
        fetchSortedData(currentOrder, 1, searchQuery); // Reset to page 1
    }

</script>
