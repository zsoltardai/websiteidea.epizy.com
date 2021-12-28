<style>
    .selection-menu {
        list-style-type: none;
        display: flex;
        flex-direction: row;
        position: relative;
        justify-content: space-evenly;
        width: 80vw;
        margin-left: 10vw;
    }
    .selection-category {
        display: flex;
        justify-content: center;
        width: auto;
        background-color: #fff;
    }
    .selection-category .icon {
        color: #007bff;
    }
    .selection-category:hover {
        width: auto;
    }
    @media only screen and (max-width: 1000px) {
        .selection-menu {
            width: 100vw;
            margin: 0;
            height: 8vh;
        }
    }
</style>

 <div class="selection-menu">
    <a class="selection-category" title="Profile" href="profile.php?id=<?php echo($_SESSION['id']); ?>">
        <i class="icon far fa-id-card"></i>
    </a>
    <a disabled class="selection-category" title="Messages">
        <i style="color: grey;" class="icon far fa-envelope"></i>
    </a>
    <a disabled class="selection-category" title="Friends">
        <i style="color: grey;" class="icon fas fa-users"></i>
    </a>
</div>