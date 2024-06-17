// const navLinks = document.querySelectorAll('.nav-links a');
// 	const disableNavLinks = () => {
//         navLinks.forEach(link => {
//             if (link.href.includes('/profile') || link.href.includes('/select')) {
//                 link.classList.remove('disabled');
//             } else {
//                 link.classList.add('disabled');
//             }
//         });
//     };

//     const enableNavLinks = () => {
//         navLinks.forEach(link => {
//             link.classList.remove('disabled');
//         });
//     };

//     const checkChildSelection = () => {
//         const selectedChild = localStorage.getItem('selectedChild');
//         if (!selectedChild) {
//             disableNavLinks();
//         } else {
//             enableNavLinks();
//         }
//     };
// 	checkChildSelection(); 