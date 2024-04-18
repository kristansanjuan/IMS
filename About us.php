<head>
    <title>Dev Info</title>
    <link rel="stylesheet" href="styles.css">
    <?php include('header.php')?>
    <?php include('auth.php'); ?>
    <style>
        body {
            height: 120vh;
            width: 100vw;
            margin: 0rem;
            overflow: hidden;
        }
    </style>
</head>
<body>

    <div id="image-track" data-mouse-down-at="0" data-prev-percentage="0">
        <img src="images/dev1.png" draggable="false" width="400px" height="auto"/>
        <img src="images/dev2.png" draggable="false" width="400px" height="auto"/>
        <img src="images/dev3.png" draggable="false" width="400px" height="auto"/>
        <img src="images/dev4.png" draggable="false" width="400px" height="auto"/>
    </div>

    <script>
        const track = document.getElementById("image-track");
        const images = track.getElementsByTagName("img");
        const imageWidth = images[0].offsetWidth;
        const trackWidth = track.offsetWidth;
        const totalWidth = imageWidth * images.length;
        const maxDelta = (totalWidth - trackWidth) / 2;
    
        let direction = "right";
        let animationPaused = false;
    
        const handleOnMove = () => {
            if (!animationPaused) {
                const prevPercentage = parseFloat(track.dataset.prevPercentage);
    
                let nextPercentage = prevPercentage;
                if (direction === "right") {
                    nextPercentage -= 0.6;
                    if (nextPercentage <= -100) {
                        direction = "left";
                    }
                } else {
                    nextPercentage += 0.6;
                    if (nextPercentage >= -55) {
                        nextPercentage = -55;
                        direction = "right";
                    }
                }
    
                track.dataset.prevPercentage = nextPercentage;
                track.style.transform = `translate(${nextPercentage}%, -50%)`;
    
                for (const image of images) {
                    const imagePercentage = (nextPercentage / -100) * (totalWidth - trackWidth);
                }
            }
        };
    
        setInterval(handleOnMove, 20);
        
        track.addEventListener("click", () => {
            animationPaused = !animationPaused;
        });
    </script>
</body>