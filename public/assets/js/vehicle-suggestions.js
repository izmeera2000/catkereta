(() => {
    const makeInput = document.getElementById('vehicle_make');
    const modelList = document.getElementById('vehicle-models');

    if (!makeInput || !modelList) {
        return;
    }

    const modelsByMake = {
        'perodua': ['Alza', 'Ativa', 'Axia', 'Aruz', 'Bezza', 'Myvi', 'Viva'],
        'proton': ['Exora', 'Iriz', 'Persona', 'S70', 'Saga', 'X50', 'X70', 'X90'],
        'toyota': ['Alphard', 'Avanza', 'Camry', 'Corolla', 'Fortuner', 'Hilux', 'Innova', 'Vios', 'Yaris'],
        'honda': ['Accord', 'BR-V', 'City', 'Civic', 'CR-V', 'HR-V', 'Jazz'],
        'nissan': ['Almera', 'Navara', 'Serena', 'X-Trail'],
        'mazda': ['Mazda2', 'Mazda3', 'CX-3', 'CX-5', 'CX-8', 'CX-30'],
        'mitsubishi': ['ASX', 'Attrage', 'Outlander', 'Triton', 'Xpander'],
        'suzuki': ['Jimny', 'Swift', 'Vitara'],
        'isuzu': ['D-Max', 'MU-X'],
        'hyundai': ['Elantra', 'Santa Fe', 'Staria', 'Tucson'],
        'kia': ['Carnival', 'Cerato', 'Sportage'],
        'mercedes-benz': ['A-Class', 'C-Class', 'E-Class', 'GLA', 'GLC'],
        'bmw': ['1 Series', '3 Series', '5 Series', 'X1', 'X3', 'X5'],
    };

    const updateModelSuggestions = () => {
        const make = makeInput.value.trim().toLocaleLowerCase();
        const models = modelsByMake[make] || [];

        modelList.replaceChildren(...models.map((model) => {
            const option = document.createElement('option');
            option.value = model;

            return option;
        }));
    };

    makeInput.addEventListener('input', updateModelSuggestions);
    updateModelSuggestions();
})();
