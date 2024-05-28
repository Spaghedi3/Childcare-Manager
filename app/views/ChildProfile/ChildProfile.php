<body>
    <div class="container" style="max-width: 1000px;">
        <h1 id="child-name">Welcome to [Child's] Profile</h1>
        <div class="child-img-div">
            <img id="child-image" src="" alt="Child's Picture">
        </div>
        <p id="child-description">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus malesuada, sapien nec lacinia lacinia, odio purus cursus odio, vitae efficitur nisl libero at eros. Ut ullamcorper, mauris eu cursus viverra, est lorem consequat lorem, et gravida metus urna sit amet erat.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ut ipsum arcu. Aenean ut tortor sed lorem tristique bibendum. Nulla facilisi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Duis ac ligula non lectus auctor imperdiet in at justo.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce vel odio at augue fermentum scelerisque. In id justo id purus pretium aliquet. Etiam varius metus ac nulla accumsan, at facilisis elit elementum. Ut id enim at magna eleifend venenatis. Aliquam erat volutpat.
        </p>
        <h2>Get Started</h2>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed interdum mauris nec nunc dignissim, a vestibulum eros ultrices. Donec vulputate augue ut augue sagittis, sit amet vehicula purus dignissim.
        </p>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const childId = urlParams.get('id');

        const fetchChildProfile = async (id) => {
            try {
                const response = await fetch(`/getChildProfile?id=${id}`, { 
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const profile = await response.json();
                document.getElementById('child-name').textContent = `Welcome to ${profile.name}'s Profile`;
                document.getElementById('child-image').src = profile.profile_picture_path;
            } catch (error) {
                console.error('Error fetching child profile:', error);
            }
        };

        if (childId) {
            fetchChildProfile(childId);
        } else {
            console.error('Child ID not found in URL');
        }
    });
</script>

