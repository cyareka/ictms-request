<div class="requests">
   <div class="filter">
      <div class="row height d-flex justify-content-left align-items-left">
         <div class="col-md-6">
            <div class="form">
               <i class="fa fa-search"></i>
               <input type="text" class="form-control form-input" placeholder="Search">
            </div>
         </div>
      </div>
      <div class="tableactions">
         <div id="divide">
            <i class="bi bi-arrow-left-short"></i>
            <i class="bi bi-arrow-right-short" id="iconborder"></i>
            <div class="dropdown" style="float:right;">
               <button class="dropbtn"><i class="bi bi-filter"></i></button>
               <div class="dropdown-content">
                  <p id="filterlabel">Filter By</p>
                  <hr>
                  <p>Conference Room</p>
                  <a>
                     <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                        MAAGAP Conference
                        </label>
                     </div>
                  </a>
                  <a>
                     <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                        MAGITING Conference
                        </label>
                     </div>
                  </a>
                  <p>Status</p>
                  <a>
                     <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                        Not Approved
                        </label>
                     </div>
                  </a>
                  <a>
                     <div class="form-check" id="margincheck">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                        Approved and Cancelled
                        </label>
                     </div>
                  </a>
                  <a>
                     <div class="form-check" id="margincheck">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                        Approved and Finished
                        </label>
                     </div>
                  </a>
                  <hr>
                  <div class="buttons">
                     <button class="cancelbtn">Remove</button>
                     <button class="applybtn">Filter</button>
                  </div>
               </div>
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
               <th scope="col">Date Requested</th>
               <th scope="col">Conference Room</th>
               <th scope="col">Requesting Office</th>
               <th scope="col">Date Needed</th>
               <th scope="col">Time Needed</th>
               <th scope="col">Availability</th>
               <th scope="col">Form Status</th>
               <th scope="col">Event Status</th>
               <th scope="col"></th>
            </tr>
         </thead>
         <tbody>
            <tr>
               <th scope="row">6013</th>
               <td>08-09-2024</td>
               <td>Magiting Room</td>
               <td>HR Office</td>
               <td>09-01-2024</td>
               <td>3:00 P.M</td>
               <td>Available</td>
               <td><span class="approved">Approved</span></td>
               <td>Finished</td>
               <td>
                  <a href="{{ route('ConferencelogDetail') }}"><i class="bi bi-person-vcard" id="actions"></i></a>
                  <i class="bi bi-download" id="actions"></i>
               </td>
            </tr>
            <tr>
               <th scope="row">6013</th>
               <td>08-09-2024</td>
               <td>Magiting Room</td>
               <td>HR Office</td>
               <td>09-01-2024</td>
               <td>3:00 P.M</td>
               <td>Available</td>
               <td><span class="approved">Approved</span></td>
               <td>Finished</td>
               <td>
                  <a href="{{ route('ConferencelogDetail') }}"><i class="bi bi-person-vcard" id="actions"></i></a>
                  <i class="bi bi-download" id="actions"></i>
               </td>
            </tr>
            <tr>
               <th scope="row">6013</th>
               <td>08-09-2024</td>
               <td>Magiting Room</td>
               <td>HR Office</td>
               <td>09-01-2024</td>
               <td>3:00 P.M</td>
               <td>Available</td>
               <td><span class="approved">Approved</span></td>
               <td>Finished</td>
               <td>
                  <a href="{{ route('ConferencelogDetail') }}"><i class="bi bi-person-vcard" id="actions"></i></a>
                  <i class="bi bi-download" id="actions"></i>
               </td>
            </tr>
            <tr>
               <th scope="row">6013</th>
               <td>08-09-2024</td>
               <td>Magiting Room</td>
               <td>HR Office</td>
               <td>09-01-2024</td>
               <td>3:00 P.M</td>
               <td>Available</td>
               <td><span class="approved">Approved</span></td>
               <td>Finished</td>
               <td>
                  <a href="{{ route('ConferencelogDetail') }}"><i class="bi bi-person-vcard" id="actions"></i></a>
                  <i class="bi bi-download" id="actions"></i>
               </td>
            </tr>
            <tr>
               <th scope="row">6013</th>
               <td>08-09-2024</td>
               <td>Magiting Room</td>
               <td>HR Office</td>
               <td>09-01-2024</td>
               <td>3:00 P.M</td>
               <td>Available</td>
               <td><span class="approved">Approved</span></td>
               <td>Finished</td>
               <td>
                  <a href="{{ route('ConferencelogDetail') }}"><i class="bi bi-person-vcard" id="actions"></i></a>
                  <i class="bi bi-download" id="actions"></i>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
</div>
<div class="end"></div>
