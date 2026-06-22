document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.addEventListener('click', () => {

        document.querySelectorAll('.nav-btn')
            .forEach(b => b.classList.remove('active'));

        document.querySelectorAll('.tab-content')
            .forEach(c => c.classList.remove('active'));

        btn.classList.add('active');

        const tab = document.getElementById(btn.dataset.tab);

        if (tab) {
            tab.classList.add('active');
        } else {
            console.error("Tab introuvable :", btn.dataset.tab);
        }
    });
});