<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Détails du Poste</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    .fade-in {
      animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .equipment-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .status-badge {
      transition: all 0.2s ease;
    }
    .status-fonctionnel { background-color: #dcfce7; color: #166534; }
    .status-defaillant { background-color: #fecaca; color: #991b1b; }
    .status-maintenance { background-color: #fed7aa; color: #c2410c; }
    .status-neuf { background-color: #dcfce7; color: #166534; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">
  <div class="container mx-auto px-4 py-8 max-w-6xl">
    <!-- Main Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden fade-in">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 text-white">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
          <div>
            <h1 class="text-2xl md:text-3xl font-bold mb-1">Détails du Poste Informatique</h1>
            <div class="flex items-center space-x-2 text-orange-100">
              <i class="fas fa-info-circle"></i>
              <p>Informations complètes sur le poste et ses composants</p>
            </div>
          </div>
          <button onclick="window.print()" class="mt-4 md:mt-0 bg-white text-orange-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition flex items-center">
            <i class="fas fa-print mr-2"></i> Imprimer
          </button>
        </div>
      </div>

      <!-- Poste Information Section -->
      <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-orange-600 mb-4 pb-2 border-b border-orange-200 flex items-center">
          <i class="fas fa-desktop mr-2"></i> Informations du Poste
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="flex items-center mb-2">
              <div class="bg-orange-100 p-2 rounded-full mr-3">
                <i class="fas fa-barcode text-orange-600"></i>
              </div>
              <div>
                <p class="text-sm text-gray-500">Code Poste</p>
                <p class="font-medium">{{ $poste->code_poste }}</p>
              </div>
            </div>
          </div>
          
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="flex items-center mb-2">
              <div class="bg-orange-100 p-2 rounded-full mr-3">
                <i class="fas fa-map-marker-alt text-orange-600"></i>
              </div>
              <div>
                <p class="text-sm text-gray-500">Nom du Poste</p>
                <p class="font-medium">{{ $poste->emplacement }}</p>
              </div>
            </div>
          </div>
          
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="flex items-center mb-2">
              <div class="bg-orange-100 p-2 rounded-full mr-3">
                <i class="fas fa-calendar-alt text-orange-600"></i>
              </div>
              <div>
                <p class="text-sm text-gray-500">Date Création</p>
                <p class="font-medium">{{ $poste->created_at->format('d/m/Y') }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Equipment Section -->
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-semibold text-orange-600 flex items-center">
            <i class="fas fa-microchip mr-2"></i> Composants ({{ $poste->equipements->count() }})
          </h2>
          <div class="relative">
            <button id="filterBtn" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg text-gray-700 flex items-center transition">
              <i class="fas fa-filter mr-2"></i> Filtrer
            </button>
            <div id="filterDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
              <div class="p-2">
                <label class="flex items-center px-2 py-1 hover:bg-gray-100 rounded cursor-pointer">
                  <input type="checkbox" class="mr-2" checked> Unité Centrale
                </label>
                <label class="flex items-center px-2 py-1 hover:bg-gray-100 rounded cursor-pointer">
                  <input type="checkbox" class="mr-2" checked> Écrans
                </label>
                <label class="flex items-center px-2 py-1 hover:bg-gray-100 rounded cursor-pointer">
                  <input type="checkbox" class="mr-2" checked> Claviers
                </label>
                <label class="flex items-center px-2 py-1 hover:bg-gray-100 rounded cursor-pointer">
                  <input type="checkbox" class="mr-2" checked> Souris
                </label>
              </div>
            </div>
          </div>
        </div>

        @if($poste->equipements->isEmpty())
          <div class="text-center py-12">
            <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-desktop text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun équipement associé</h3>
            <p class="text-gray-500">Ce poste n'a pas encore d'équipements associés.</p>
          </div>
        @else
          <!-- Unité Centrale Card -->
          @php $uc = $poste->equipements->where('categorie', 'unite centrale')->first(); @endphp
          @if($uc)
            <div class="equipment-card bg-green-50 border border-green-200 rounded-xl p-6 mb-6 transition-all duration-300" data-category="unite-centrale">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <div class="flex items-center">
                  <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-server text-green-600 text-xl"></i>
                  </div>
                  <h3 class="text-lg font-semibold text-green-800">Unité Centrale</h3>
                </div>
                <span class="status-badge status-{{ $uc->etat }} px-3 py-1 rounded-full text-sm font-medium mt-2 md:mt-0 inline-block">
                  <i class="fas fa-circle text-xs mr-1"></i> {{ ucfirst($uc->etat) }}
                </span>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white p-3 rounded-lg shadow-sm">
                  <p class="text-sm text-gray-500">N° Série</p>
                  <p class="font-medium">{{ $uc->numero_serie }}</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                  <p class="text-sm text-gray-500">Marque/Modèle</p>
                  <p class="font-medium">{{ $uc->marque ?? 'N/A' }} {{ $uc->modele ?? '' }}</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                  <p class="text-sm text-gray-500">Processeur</p>
                  <p class="font-medium">{{ $uc->processeur ?? 'N/A' }}</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                  <p class="text-sm text-gray-500">Mémoire RAM</p>
                  <p class="font-medium">{{ $uc->ram ?? 'N/A' }}</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                  <p class="text-sm text-gray-500">Système d'exploitation</p>
                  <p class="font-medium">{{ $uc->systeme ?? 'N/A' }}</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                  <p class="text-sm text-gray-500">Date d'installation</p>
                  <p class="font-medium">{{ $uc->created_at->format('d/m/Y') }}</p>
                </div>
              </div>
            </div>
          @endif

          <!-- Other Equipment Tables -->
          <div class="space-y-6">
            @foreach($poste->equipements->whereIn('categorie', ['ecran', 'clavier', 'souris'])->groupBy('categorie') as $categorie => $equipements)
              <div class="equipment-section" data-category="{{ $categorie }}">
                <div class="flex items-center bg-green-700 text-white px-4 py-2 rounded-t-lg">
                  @if($categorie == 'ecran')
                    <i class="fas fa-tv mr-2"></i>
                    <h3 class="font-semibold uppercase text-sm tracking-wide">Écrans</h3>
                  @elseif($categorie == 'clavier')
                    <i class="fas fa-keyboard mr-2"></i>
                    <h3 class="font-semibold uppercase text-sm tracking-wide">Claviers</h3>
                  @elseif($categorie == 'souris')
                    <i class="fas fa-mouse mr-2"></i>
                    <h3 class="font-semibold uppercase text-sm tracking-wide">Souris</h3>
                  @endif
                </div>
                <div class="overflow-x-auto">
                  <table class="min-w-full bg-white border border-gray-200 rounded-b-lg">
                    <thead class="bg-green-50 text-green-700">
                      <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">N° Série</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Marque</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Modèle</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">État</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Date</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                      @foreach($equipements as $equipement)
                        <tr class="hover:bg-gray-50">
                          <td class="px-4 py-3">{{ $equipement->numero_serie }}</td>
                          <td class="px-4 py-3">{{ $equipement->marque ?? 'N/A' }}</td>
                          <td class="px-4 py-3">{{ $equipement->modele ?? 'N/A' }}</td>
                          <td class="px-4 py-3">
                            <span class="status-badge status-{{ $equipement->etat }} px-2 py-1 rounded-full text-xs font-medium">
                              <i class="fas fa-circle text-xs mr-1"></i> {{ ucfirst($equipement->etat) }}
                            </span>
                          </td>
                          <td class="px-4 py-3">{{ $equipement->created_at->format('d/m/Y') }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>

  <script>
    // Toggle filter dropdown
    document.getElementById('filterBtn').addEventListener('click', function() {
      const dropdown = document.getElementById('filterDropdown');
      dropdown.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
      const filterBtn = document.getElementById('filterBtn');
      const dropdown = document.getElementById('filterDropdown');
      
      if (!filterBtn.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
      }
    });

    // Filter functionality
    document.querySelectorAll('#filterDropdown input[type="checkbox"]').forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        const category = this.parentElement.textContent.trim().toLowerCase();
        let targetCategory = '';
        
        if (category.includes('unité centrale')) {
          targetCategory = 'unite-centrale';
        } else if (category.includes('écrans')) {
          targetCategory = 'ecran';
        } else if (category.includes('claviers')) {
          targetCategory = 'clavier';
        } else if (category.includes('souris')) {
          targetCategory = 'souris';
        }
        
        const sections = document.querySelectorAll(`[data-category="${targetCategory}"]`);
        sections.forEach(section => {
          section.style.display = this.checked ? 'block' : 'none';
        });
      });
    });
  </script>
</body>
</html>