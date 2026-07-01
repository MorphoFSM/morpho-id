@if(Auth::check() || Auth::guard('admin')->check())
    @php
        $role = Auth::guard('admin')->check() ? 'Admin' : 'User';
        $userName = Auth::guard('admin')->check() ? Auth::guard('admin')->user()->name : Auth::user()->name;
    @endphp

    <div id="morpho-chatbot" class="morpho-chatbot" data-role="{{ $role }}" data-name="{{ $userName }}">
        <button id="chatbot-toggle" class="chatbot-toggle">
            <span class="chatbot-icon">💬</span>
        </button>

        <div id="chatbot-window" class="chatbot-window hidden">
            <div class="chatbot-header">
                <div class="chatbot-title">
                    <div class="chatbot-avatar">🤖</div>
                    <div class="chatbot-info">
                        <h4>MorphoAssistant</h4>
                        <p>{{ $role }} Guide</p>
                    </div>
                </div>
                <button id="chatbot-close" class="chatbot-close">&times;</button>
            </div>
            
            <div id="chatbot-messages" class="chatbot-messages">
                <!-- Messages will appear here -->
            </div>
            
            <div id="chatbot-options" class="chatbot-options">
                <!-- Suggestion Buttons -->
            </div>
            
            <div class="chatbot-input-area">
                <input type="text" id="chatbot-input" placeholder="Ask me anything...">
                <button id="chatbot-send">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </div>
        </div>
    </div>

    <style>
        .morpho-chatbot { position: fixed; bottom: 30px; right: 30px; z-index: 9999; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .chatbot-toggle { width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #00F0FF, #9D4EDD); border: none; color: white; font-size: 28px; cursor: pointer; box-shadow: 0 5px 15px rgba(0, 240, 255, 0.4); transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; }
        .chatbot-toggle:hover { transform: scale(1.1); box-shadow: 0 8px 25px rgba(157, 78, 221, 0.6); }
        .chatbot-window { position: absolute; bottom: 80px; right: 0; width: 360px; height: 550px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(0, 240, 255, 0.2); border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); display: flex; flex-direction: column; overflow: hidden; transition: all 0.3s ease; transform-origin: bottom right; }
        .chatbot-window.hidden { opacity: 0; transform: scale(0.1); pointer-events: none; }
        .chatbot-header { background: rgba(0, 0, 0, 0.4); padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        .chatbot-title { display: flex; align-items: center; gap: 12px; }
        .chatbot-avatar { font-size: 24px; background: rgba(0, 240, 255, 0.1); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(0, 240, 255, 0.3); }
        .chatbot-info h4 { margin: 0; color: #fff; font-size: 15px; font-weight: 700; }
        .chatbot-info p { margin: 0; color: #00F0FF; font-size: 12px; font-weight: 600; }
        .chatbot-close { background: transparent; border: none; color: #a0aec0; font-size: 24px; cursor: pointer; transition: color 0.2s; }
        .chatbot-close:hover { color: #ef4444; }
        .chatbot-messages { flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; scroll-behavior: smooth; }
        .chatbot-messages::-webkit-scrollbar { width: 6px; }
        .chatbot-messages::-webkit-scrollbar-track { background: transparent; }
        .chatbot-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .chat-bubble { max-width: 85%; padding: 12px 16px; border-radius: 14px; font-size: 14px; line-height: 1.5; animation: fadeIn 0.3s ease-out forwards; }
        .chat-bot { background: rgba(30, 41, 59, 0.9); color: #e2e8f0; align-self: flex-start; border-bottom-left-radius: 4px; border: 1px solid rgba(255,255,255,0.05); }
        .chat-bot strong { color: #00F0FF; }
        .chat-user { background: linear-gradient(135deg, rgba(0, 240, 255, 0.2), rgba(157, 78, 221, 0.2)); color: #fff; align-self: flex-end; border-bottom-right-radius: 4px; border: 1px solid rgba(0, 240, 255, 0.3); }
        .chatbot-options { padding: 10px 15px; background: rgba(0, 0, 0, 0.3); border-top: 1px solid rgba(255, 255, 255, 0.05); display: flex; flex-direction: column; gap: 6px; }
        .chat-btn { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); padding: 8px 12px; border-radius: 8px; color: #cbd5e0; font-size: 13px; cursor: pointer; text-align: left; transition: all 0.2s; display: flex; justify-content: space-between; align-items: center; }
        .chat-btn:hover { background: rgba(0, 240, 255, 0.15); border-color: rgba(0, 240, 255, 0.4); color: #fff; padding-left: 16px; }
        .chatbot-input-area { display: flex; padding: 15px; background: rgba(0, 0, 0, 0.5); border-top: 1px solid rgba(255,255,255,0.05); gap: 10px; }
        #chatbot-input { flex: 1; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); padding: 10px 15px; border-radius: 20px; color: white; font-size: 14px; outline: none; transition: 0.3s; }
        #chatbot-input:focus { border-color: #00F0FF; background: rgba(0, 240, 255, 0.05); }
        #chatbot-send { background: linear-gradient(135deg, #00F0FF, #9D4EDD); border: none; color: white; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.3s; }
        #chatbot-send:hover { transform: scale(1.1); }
        .typing-indicator { display: inline-flex; align-items: center; gap: 4px; padding: 12px 16px; background: rgba(30, 41, 59, 0.9); border-radius: 14px; border-bottom-left-radius: 4px; width: fit-content; }
        .typing-dot { width: 6px; height: 6px; background: #a0aec0; border-radius: 50%; animation: typing 1.4s infinite ease-in-out; }
        .typing-dot:nth-child(1) { animation-delay: 0s; } .typing-dot:nth-child(2) { animation-delay: 0.2s; } .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing { 0%, 100% { transform: scale(1); opacity: 0.5; } 50% { transform: scale(1.5); opacity: 1; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatbot = document.getElementById('morpho-chatbot');
            if (!chatbot) return;

            const toggleBtn = document.getElementById('chatbot-toggle');
            const closeBtn = document.getElementById('chatbot-close');
            const window = document.getElementById('chatbot-window');
            const messagesContainer = document.getElementById('chatbot-messages');
            const optionsContainer = document.getElementById('chatbot-options');
            const inputField = document.getElementById('chatbot-input');
            const sendBtn = document.getElementById('chatbot-send');
            
            const role = chatbot.getAttribute('data-role');
            const userName = chatbot.getAttribute('data-name');
            
            let isChatOpen = false;
            let hasGreeted = false;

            // Bilingual Knowledge Base
            const knowledgeBase = {
                'Admin': [
                    {
                        keys: ['add', 'create', 'new', 'specimen', 'insert', 'tambah', 'cipta', 'spesimen', 'baru'],
                        ansEN: "To add a new specimen:<br>1. Go to <strong>SPECIMEN MANAGEMENT</strong> in the top navigation menu.<br>2. Click the cyan <strong>'+ Add New'</strong> button.<br>3. Fill in the details (name, category, 3D model .glb file).<br>4. Click Save.",
                        ansMS: "Untuk tambah spesimen baru:<br>1. Pergi ke <strong>SPECIMEN MANAGEMENT</strong> di menu atas.<br>2. Tekan butang cyan <strong>'+ Add New'</strong>.<br>3. Isi maklumat (nama, kategori, fail 3D .glb).<br>4. Tekan Save."
                    },
                    {
                        keys: ['approve', 'reject', 'role', 'request', 'admin', 'lulus', 'tolak', 'mohon', 'permohonan'],
                        ansEN: "To manage role requests:<br>1. Go to <strong>ROLE REQUESTS</strong> in the top navigation menu.<br>2. Find the user's request.<br>3. Click the green <strong>'Approve'</strong> or red 'Reject' button.<br>4. The system will auto-email them.",
                        ansMS: "Untuk uruskan permohonan admin:<br>1. Pergi ke <strong>ROLE REQUESTS</strong> di menu atas.<br>2. Search nama pemohon.<br>3. Tekan butang hijau <strong>'Approve'</strong> atau merah 'Reject'.<br>4. Sistem akan hantar e-mel secara automatik."
                    },
                    {
                        keys: ['visit', 'log', 'history', 'management', 'delete', 'export', 'lawatan', 'rekod', 'buang', 'padam'],
                        ansEN: "The <strong>Visit Management</strong> page records every login. You can monitor time, IDs, and status. To clear old logs, check the boxes and click <strong>'DELETE SELECTED'</strong>, or click <strong>'EXPORT EXCEL'</strong> to save a copy.",
                        ansMS: "Halaman <strong>Visit Management</strong> merekod setiap log masuk. Bos boleh semak masa dan status. Untuk padam rekod, tandakan kotak dan tekan <strong>'DELETE SELECTED'</strong>, atau tekan <strong>'EXPORT EXCEL'</strong> untuk simpan."
                    },
                    {
                        keys: ['profile', 'password', 'picture', 'avatar', 'profil', 'gambar', 'kata laluan', 'tukar'],
                        ansEN: "You can change your profile picture or password by clicking <strong>Profile</strong> in the top right corner of the navigation bar.",
                        ansMS: "Bos boleh tukar gambar profil atau kata laluan dengan klik <strong>Profile</strong> di penjuru kanan atas bar navigasi."
                    }
                ],
                'User': [
                    {
                        keys: ['rotate', 'zoom', 'move', '3d', 'model', 'view', 'pusing', 'gerak', 'lihat'],
                        ansEN: "When viewing a 3D model in Explore mode:<br>1. <strong>Left Click + Drag</strong> to rotate the model.<br>2. <strong>Scroll Mouse</strong> to zoom in/out.<br>3. <strong>Right Click + Drag</strong> to pan the camera.",
                        ansMS: "Bila lihat model 3D di halaman Explore:<br>1. <strong>Klik Kiri & Seret</strong> untuk pusing model.<br>2. <strong>Scroll Mouse</strong> untuk zoom in/out.<br>3. <strong>Klik Kanan & Seret</strong> untuk alihkan kamera."
                    },
                    {
                        keys: ['compare', 'comparison', 'diff', 'banding', 'beza'],
                        ansEN: "The <strong>Comparison Mode</strong> lets you view two specimens side-by-side! Go to Explore, click <strong>'Compare'</strong> on any two specimens, then click the floating <strong>'Compare Selected'</strong> button.",
                        ansMS: "Fungsi <strong>Comparison</strong> membolehkan bos bandingkan dua spesimen bersebelahan! Pergi ke Explore, tekan <strong>'Compare'</strong> pada dua spesimen, dan tekan butang terapung <strong>'Compare Selected'</strong> di bawah."
                    },
                    {
                        keys: ['apply', 'become admin', 'request admin', 'mohon admin', 'jadi admin'],
                        ansEN: "Lecturers/Staff can request Admin access. Go to <strong>My Profile</strong> (top right), scroll down to <strong>Request Admin Role</strong>, enter your reason, and submit!",
                        ansMS: "Hanya staf/pensyarah boleh mohon. Pergi ke <strong>My Profile</strong> (penjuru kanan atas), skrol ke bawah bahagian <strong>Request Admin Role</strong>, isi sebab dan hantar!"
                    },
                    {
                        keys: ['taxonomy', 'phylum', 'class', 'family', 'genus', 'taksonomi', 'kategori'],
                        ansEN: "When viewing a specimen, look at the information panel on the right (or bottom on mobile). You will see the full taxonomic hierarchy: <strong>Phylum, Class, Order, Family, and Genus</strong>.",
                        ansMS: "Semasa melihat spesimen, rujuk panel maklumat di sebelah kanan (atau bawah di telefon). Bos akan nampak hierarki penuh: <strong>Phylum, Class, Order, Family, dan Genus</strong>."
                    }
                ]
            };

            // Detect language based on common Malay keywords
            function isMalay(text) {
                const malayWords = ['macam', 'mana', 'nak', 'apa', 'fungsi', 'cara', 'boleh', 'tambah', 'buang', 'tukar', 'buat', 'saya', 'tak', 'bila'];
                const words = text.toLowerCase().split(' ');
                return words.some(w => malayWords.includes(w));
            }

            // Find best answer
            function findAnswer(query) {
                query = query.toLowerCase();
                const db = knowledgeBase[role];
                
                for (let item of db) {
                    if (item.keys.some(key => query.includes(key))) {
                        return isMalay(query) ? item.ansMS : item.ansEN;
                    }
                }
                
                return isMalay(query) 
                    ? "Maaf, saya tidak pasti mengenai soalan itu. Boleh cuba gunakan kata kunci lain seperti 'tambah spesimen', 'compare', atau 'profile'?"
                    : "I'm sorry, I don't have information on that. Try using keywords like 'add specimen', 'compare', or 'profile'.";
            }

            const initialOptions = role === 'Admin' 
                ? ["How to add a Specimen?", "How to approve requests?", "Explain Visit Management"]
                : ["How to view 3D models?", "How to use Comparison mode?", "How to apply for Admin?"];

            toggleBtn.addEventListener('click', () => {
                isChatOpen = !isChatOpen;
                if (isChatOpen) {
                    window.classList.remove('hidden');
                    if (!hasGreeted) {
                        showGreeting();
                        hasGreeted = true;
                    }
                } else {
                    window.classList.add('hidden');
                }
            });

            closeBtn.addEventListener('click', () => {
                isChatOpen = false;
                window.classList.add('hidden');
            });

            function addMessage(text, sender = 'bot') {
                const bubble = document.createElement('div');
                bubble.className = `chat-bubble chat-${sender}`;
                bubble.innerHTML = text;
                messagesContainer.appendChild(bubble);
                scrollToBottom();
            }

            function addTypingIndicator() {
                const indicator = document.createElement('div');
                indicator.className = 'typing-indicator';
                indicator.innerHTML = `<div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>`;
                messagesContainer.appendChild(indicator);
                scrollToBottom();
                return indicator;
            }

            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function renderOptions() {
                optionsContainer.innerHTML = ''; 
                initialOptions.forEach(opt => {
                    const btn = document.createElement('button');
                    btn.className = 'chat-btn';
                    btn.innerHTML = `${opt} <span>›</span>`;
                    btn.addEventListener('click', () => processUserMessage(opt));
                    optionsContainer.appendChild(btn);
                });
            }

            function processUserMessage(text) {
                optionsContainer.innerHTML = '';
                addMessage(text, 'user');
                const indicator = addTypingIndicator();
                
                setTimeout(() => {
                    indicator.remove();
                    addMessage(findAnswer(text), 'bot');
                }, 1000);
            }

            sendBtn.addEventListener('click', () => {
                const text = inputField.value.trim();
                if(text) {
                    inputField.value = '';
                    processUserMessage(text);
                }
            });

            inputField.addEventListener('keypress', (e) => {
                if(e.key === 'Enter') sendBtn.click();
            });

            function showGreeting() {
                addMessage(`👋 Hello <strong>${userName}</strong>!`);
                const indicator = addTypingIndicator();
                
                setTimeout(() => {
                    indicator.remove();
                    if (role === 'Admin') {
                        addMessage('I am your <strong>Admin Assistant</strong>. How can I help you manage the system today? (You can type in English or Malay)');
                    } else {
                        addMessage('I am your Morpho.ID <strong>System Guide</strong>. How can I help you explore today? (You can type in English or Malay)');
                    }
                    renderOptions();
                }, 1000);
            }
        });
    </script>
@endif
