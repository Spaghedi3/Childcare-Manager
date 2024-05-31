<div class="nav">
    <input type="checkbox" id="nav-check">
    <div class="nav-header">
        <div class="nav-title">
            <a href="#" id="childProfileLink">Childcare Manager</a>
        </div>
    </div>
    <div class="nav-btn">
        <label for="nav-check">
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>
    <div class="nav-links">
        <a href="/select">Select Child Profile</a>
        <a href="/schedule">Schedule</a>
        <a href="/medical">Medical Info</a>
        <a href="/media">Media</a>
        <a href="/relationships">Relationships</a>
        <a href="/timeline">Timeline</a>
        <a href="/profile">Profile</a>
    </div>
</div>

<!-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        const getCookie = (name) => {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        };

        const selectedChildId = getCookie('child_id');
        if (selectedChildId) {
            document.getElementById('childProfileLink').href = `/childProfile?id=${selectedChildId}`;
        } else {
            document.getElementById('childProfileLink').href = '/childProfile';
        }
    });
</script> -->
