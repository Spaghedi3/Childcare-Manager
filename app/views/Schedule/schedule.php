    <div class="schedule">
        <div class="add-event">
            <h1>Schedule</h1>
            <h2>Add new event:</h2>
            <form action="#" method="post">
                <label for="datepicker">Select Date:</label>
                <input type="datetime-local" id="datepicker" name="datepicker">
                <input type="text" id="event" name="event" placeholder="Event">
                <input type="submit" value="Submit">
            </form>
        </div>
        <div class="events">
            <h2>Events:</h2>
            <!-- Events will be imported from the database here -->
            <!-- Some of them will be recurring events -->
            <p>Eat breakfast: 08:00 (Every day)</p>
            <p>Event 1: 12/12/2020 12:00 PM</p>
            <p>Go to the park</p>
            <p>Event 2: 12/12/2020 15:00 PM</p>
            <p>Go to the aquarium</p>
        </div>
    </div>