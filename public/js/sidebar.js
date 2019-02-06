var onHamburgerClick = () => {
    let sidebar = document.querySelector(".myCustomSidebar");
    if (sidebar.classList.contains("visible")) {
      sidebar.classList.remove("visible");
      sidebar.classList.add("hidden");
    } else {
      sidebar.classList.remove("hidden");
      sidebar.classList.add("visible");
    }
  };
  