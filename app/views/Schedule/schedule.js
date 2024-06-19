const hours = Array.from({ length: 24 }, (_, i) => i);
const days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
let isEditing = false;
let currentPopupCell = null;
let timetableData = {};

document.addEventListener("DOMContentLoaded", async () => {
    const timetable = document.getElementById("timetable");

    await fetchSchedule(); // Fetch the initial timetable data

    createTimetable(timetable);

    const editButton = document.getElementById("edit-button");
    const saveButton = document.getElementById("save-button");

    editButton.addEventListener("click", () => {
        isEditing = true;
        toggleEditingMode();
    });

    saveButton.addEventListener("click", async () => {
        await updateSchedule();
        isEditing = false;
        closePopup();
        console.log(JSON.stringify(timetableData));
        toggleEditingMode();
    });

    document.addEventListener("click", (event) => {
        if (isEditing && !event.target.closest("#popup") && !event.target.closest("td")) {
            closePopup();
        }
    });

    hideEmptyRows();
    mergeCells();
});

function createTimetable(container) {
    const table = document.createElement("table");
    const thead = document.createElement("thead");
    const tbody = document.createElement("tbody");

    // Create header row
    const headerRow = document.createElement("tr");
    headerRow.appendChild(document.createElement("th")); // Empty corner cell

    days.forEach(day => {
        const th = document.createElement("th");
        th.textContent = day;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Create body rows
    hours.forEach(hour => {
        const row = document.createElement("tr");
        row.dataset.hour = hour;
        const timeCell = document.createElement("td");
        timeCell.textContent = `${hour}:00`;
        row.appendChild(timeCell);

        days.forEach(day => {
            const cell = document.createElement("td");
            cell.dataset.day = day;
            cell.dataset.hour = hour;
            cell.classList.add("bullet-cell");

            // Populate cell with existing data
            const cellData = getCellData(cell);
            if (cellData.type === "sleep") {
                cell.innerHTML = "Sleep";
                cell.className = "sleep";
            } else if (cellData.type === "feed") {
                cell.className = "feed";
                if (cellData.items.length > 0) {
                    cell.innerHTML = cellData.items.map(item => `&bull; ${item}`).join("<br>");
                } else {
                    cell.innerHTML = "Feed"; // Just show Feed if there are no items
                }
            }

            // Add click event listener to each cell
            cell.addEventListener("click", () => { setSleep(cell); closePopup(); });

            cell.addEventListener("contextmenu", (event) => {
                event.preventDefault(); // Prevent default context menu
                setFeed(cell);
                openPopup(cell);
            });

            row.appendChild(cell);
        });
        tbody.appendChild(row);
    });

    table.appendChild(tbody);
    container.appendChild(table);

    // Create the popup
    const popup = document.createElement("div");
    popup.id = "popup";
    popup.style.display = "none";
    popup.style.position = "absolute";
    popup.innerHTML = `
        <div id="popup-inputs"></div>
        <button id="popup-clear">Clear</button>
    `;
    document.body.appendChild(popup);

    document.getElementById("popup-clear").addEventListener("click", clearCell);
}

function setSleep(cell) {
    if (!isEditing) return;

    if (!cell.classList.contains("sleep")) {
        cell.innerHTML = "Sleep";
        cell.className = "sleep";
        saveCellData(cell, "sleep", []);
    }
    else {
        cell.innerHTML = "";
        cell.className = "";
        timetableData[cell.dataset.day][cell.dataset.hour] = null;
    }
}

function setFeed(cell) {
    if (!isEditing) return;

    if (!cell.classList.contains("feed")) {
        cell.innerHTML = "Feed"; // Clear existing content
        cell.className = "feed";
        addNewInput();
        saveCellData(cell, "feed", []);
    }
}

function openPopup(cell) {
    if (!isEditing) return;

    currentPopupCell = cell;

    const popup = document.getElementById("popup");
    const rect = cell.getBoundingClientRect();
    const popupWidth = popup.offsetWidth;
    const screenWidth = window.innerWidth;

    popup.style.top = `${rect.top + window.scrollY}px`; // Align vertically with the cell

    if (rect.left + rect.width + popupWidth > screenWidth) {
        // Position the popup to the left if it exceeds the screen width
        popup.style.left = `${rect.left + window.scrollX - popupWidth}px`;
    } else {
        // Position the popup to the right by default
        popup.style.left = `${rect.left + window.scrollX + rect.width}px`;
    }

    popup.style.display = "block";
    updatePopupContent(cell);
}

function closePopup() {
    const popup = document.getElementById("popup");
    popup.style.display = "none";
    currentPopupCell = null;
}

function addNewInput(value = "") {
    const popupInputs = document.getElementById("popup-inputs");

    const inputContainer = document.createElement("div");
    inputContainer.classList.add("input-container");

    const input = document.createElement("input");
    input.type = "text";
    input.placeholder = "Food";
    input.value = value;
    input.addEventListener("input", updateCellContent);
    input.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
            addNewInput();
            event.preventDefault();
        }
    });

    const clearButton = document.createElement("button");
    clearButton.innerHTML = "&#10006;"; // X symbol
    clearButton.classList.add("clear-button");
    clearButton.addEventListener("click", () => {
        inputContainer.remove();
        updateCellContent();
    });

    inputContainer.appendChild(input);
    inputContainer.appendChild(clearButton);
    popupInputs.appendChild(inputContainer);

    input.focus(); // Focus on the new input field
}

function updatePopupContent(cell) {
    const popupInputs = document.getElementById("popup-inputs");
    popupInputs.innerHTML = "";

    const cellData = getCellData(cell);

    if (cellData.type === "feed") {
        cellData.items.forEach(item => {
            addNewInput(item);
        });
        addNewInput();
    }
}

function clearCell() {
    if (!currentPopupCell) return;
    currentPopupCell.innerHTML = "";
    currentPopupCell.className = "";
    timetableData[currentPopupCell.dataset.day][currentPopupCell.dataset.hour] = null;
    closePopup();
}

function updateCellContent() {
    if (!currentPopupCell) return;

    const cell = currentPopupCell;
    const popupInputs = document.getElementById("popup-inputs");
    const inputs = popupInputs.querySelectorAll("input[type='text']");
    const items = Array.from(inputs).map(input => input.value.trim()).filter(item => item !== "");

    if (cell.className === "feed") {
        if (items.length > 0) {
            cell.innerHTML = items.map(item => `&bull; ${item}`).join("<br>");
        } else {
            cell.innerHTML = "Feed"; // Just show Feed if there are no items
        }
    }
    saveCellData(cell, cell.className, items);
}

function saveCellData(cell, type, items) {
    const day = cell.dataset.day;
    const hour = cell.dataset.hour;

    if (!timetableData[day]) {
        timetableData[day] = {};
    }
    timetableData[day][hour] = { type, items };
}

function getCellData(cell) {
    const day = cell.dataset.day;
    const hour = cell.dataset.hour;

    if (timetableData[day] && timetableData[day][hour]) {
        return timetableData[day][hour];
    }
    return { type: "", items: [] };
}

function toggleEditingMode() {
    const editButton = document.getElementById("edit-button");
    const saveButton = document.getElementById("save-button");

    if (isEditing) {
        editButton.style.display = "none";
        saveButton.style.display = "inline-block";
        document.querySelectorAll("tr[data-hour]").forEach(row => {
            row.classList.remove("hidden");
        });
        unmergeCells();
    } else {
        editButton.style.display = "inline-block";
        saveButton.style.display = "none";
        hideEmptyRows();
        mergeCells();
    }
}

function hideEmptyRows() {
    document.querySelectorAll("tr[data-hour]").forEach(row => {
        const cells = row.querySelectorAll("td:not(:first-child)");
        const hasValue = Array.from(cells).some(cell => cell.innerHTML.trim() !== "");
        if (!hasValue && !isEditing) {
            row.classList.add("hidden");
        } else {
            row.classList.remove("hidden");
        }
    });
}

function unmergeCells() {
    document.querySelectorAll("td").forEach(cell => {
        cell.style.display = "table-cell";
        cell.rowSpan = 1;
    });
}

function mergeCells() {
    days.forEach(day => {
        let previousCell = null;
        let rowSpan = 1;

        hours.forEach(hour => {
            const currentCell = document.querySelector(`td[data-day="${day}"][data-hour="${hour}"]`);

            if (previousCell && previousCell.innerHTML === currentCell.innerHTML && currentCell.innerHTML !== "") {
                rowSpan++;
                currentCell.style.display = "none";
                previousCell.rowSpan = rowSpan;
            } else {
                rowSpan = 1;
                previousCell = currentCell;
                currentCell.style.display = "table-cell";
                currentCell.rowSpan = rowSpan;
            }
        });
    });
}

async function fetchSchedule() {
    try {
        const response = await fetch('/api/schedule', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json;charset=UTF-8'
            },
            credentials: 'include'
        });

        if (response.ok) {
            timetableData = await response.json();
        } else {
            throw new Error('Error fetching data');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function updateSchedule() {
    const data = {
        schedule: timetableData
    };
    try {
        const response = await fetch('/api/schedule', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json;charset=UTF-8'
            },
            credentials: 'include',
            body: JSON.stringify(data)
        });

        if (response.ok) {
            const data = await response.json();
        } else {
            throw new Error('Error updating data');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}