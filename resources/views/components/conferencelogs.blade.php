<style>
      .pagination_rounded, .pagination_square {
    display: inline-block;
    margin-left:470px;
    margin-top:15px;
    }

    .pagination_rounded ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .pagination_rounded li:first-child {
        margin-left: 0px;
    }

    .pagination_rounded ul li {
        float: left;
        margin-left: 20px;
    }

    .pagination_rounded ul li a:hover {
        background: #4285f4;
        color: #fff;
        border: 1px solid #4285f4;
    }

    a:link {
    text-decoration: none;
    }

    .pagination_rounded .prev {
        margin-left: 0px;
        border-radius: 35px;
        width: 90px;
        height: 34px;
        line-height: 34px;
    }


    .pagination_rounded ul li a {
        float: left;
        color: #000000;
        border-radius: 50%;
        line-height: 30px;
        height: 30px;
        width: 30px;
        text-align: center;
        margin-bottom: 40px;
        border: 1px solid #e0e0e0;
    }

    .pagination_rounded .prev i {
        margin-right: 10px;
    }

    .pagination_rounded .next {
        border-radius: 35px;
        width: 90px;
        height: 34px;
        line-height: 34px;
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
        const itemsPerPage = 5; // Set items per page to 10
        let currentOrder = 'desc'; // Default order
        let searchQuery = ''; // Initialize searchQuery

        document.addEventListener('DOMContentLoaded', function () {
            fetchSortedData(currentOrder, currentPage, searchQuery); // Fetch data on page load

            // Listen to form submission for filtering
            document.getElementById('filterForm').addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent form from submitting normally
                searchQuery = document.getElementById('search-input').value.trim();

                // Debugging: Log the search query
                console.log('Search Query:', searchQuery);

                fetchSortedData(currentOrder, 1, searchQuery); // Fetch data based on search query and reset to page 1
            });

            // Reset filters when clicking reset button
            document.querySelector('.cancelbtn').addEventListener('click', function () {
                resetFilters();
            });
        });

        // Sort by Date Requested
        document.getElementById('sort-date-requested').addEventListener('click', function (e) {
            e.preventDefault();
            currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
            this.setAttribute('data-order', currentOrder);

            // Debugging: Log the current order
            console.log('Current Order:', currentOrder);

            fetchSortedData(currentOrder, 1, searchQuery); // Reset to page 1 after sorting
        });

        // Fetch sorted, paginated, and filtered data
        function fetchSortedData(order = 'desc', page = 1, search = '') {
            const params = new URLSearchParams({
                sort: 'created_at',
                order: order,
                page: page,
                per_page: itemsPerPage,
                search: search // Pass search query here
            }).toString();

            // Debugging: Log the fetch URL and params
            console.log('Fetching with URL:', `/fetchSortedLogRequests?${params}`);

            fetch(`/fetchSortedLogRequests?${params}`)
                .then(response => response.json())
                .then(data => {
                    // Debugging: Log the returned data
                    console.log('Data received:', data);
                    updateTable(data.data);
                    updatePagination(data.pagination);
                })
                .catch(error => console.error('Error fetching sorted data:', error));
        }

        // Update table rows
        function updateTable(data) {
            const tbody = document.getElementById('requests-tbody');
            tbody.innerHTML = '';

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
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="9">No requests found.</td></tr>';
            }
        }

        // Update pagination links
        function updatePagination(pagination) {
            const paginationList = document.getElementById('pagination-list');
            paginationList.innerHTML = '';

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
                const pageItem = createPaginationItem(i, i);
                if (i === current_page) {
                    pageItem.classList.add('active');
                    const pageLink = pageItem.querySelector('a');
                    pageLink.style.color = 'white';
                    pageLink.style.backgroundColor = '#4285f4';
                }
                paginationList.appendChild(pageItem);
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

        // Reset filters
        function resetFilters() {
            document.getElementById('filterForm').reset();
            searchQuery = ''; // Clear the search query

            // Debugging: Log resetting action
            console.log('Filters reset. Fetching default data.');

            fetchSortedData(currentOrder, 1, searchQuery); // Fetch data with default filters and reset to page 1
        }
    </script>
