<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use Carbon\Carbon;

class AgendamentoController extends Controller
{
    public function index()
    {
        $agendamentos = Agendamento::all();
        return response()->json($agendamentos);
    }

    public function show($id)
    {
        $agendamento = Agendamento::with('usuario')->find($id);
    
        if (!$agendamento) {
            return response()->json(['message' => 'Agendamento não encontrado'], 404);
        }
    
        return response()->json([
            'id' => $agendamento->id,
            'usuario_id' => $agendamento->usuario_id,
            'hora_inicio' => $agendamento->hora_inicio,
            'hora_fim' => $agendamento->hora_fim,
            'data' => $agendamento->data->format('Y-m-d'),
            'avaliacao' => $agendamento->avaliacao,
            'confirmado' => $agendamento->confirmado,
            'dia' => $agendamento->data->format('d'),
            'mes' => $agendamento->data->format('m'),
            'ano' => $agendamento->data->format('Y'),
            'usuario' => [
                'id' => $agendamento->usuario->id,
                'nome' => $agendamento->usuario->nome,
                'identificador' => $agendamento->usuario->identificador,
            ]
        ]);
    }
    public function getByUsuarioId()
    {
        $usuario = auth()->user();
    
        if (!$usuario) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }
    
        $agendamentos = Agendamento::with('usuario')
            ->where('usuario_id', $usuario->id)
            ->get();
    
        if ($agendamentos->isEmpty()) {
            return response()->json(['message' => 'Nenhum agendamento encontrado para o usuário especificado'], 204);
        }
    
        $agendamentosFormatados = $agendamentos->map(function ($agendamento) {
            return [
                'id' => $agendamento->id,
                'usuario_id' => $agendamento->usuario_id,
                'hora_inicio' => $agendamento->hora_inicio,
                'hora_fim' => $agendamento->hora_fim,
                'data' => $agendamento->data->format('Y-m-d'),
                'avaliacao' => $agendamento->avaliacao,
                'confirmado' => $agendamento->confirmado,
                'dia' => $agendamento->data->format('d'),
                'mes' => $agendamento->data->format('m'),
                'ano' => $agendamento->data->format('Y'),
                'usuario' => [
                    'id' => $agendamento->usuario->id,
                    'nome' => $agendamento->usuario->nome,
                    'identificador' => $agendamento->usuario->identificador,
                ]
            ];
        });
    
        return response()->json($agendamentosFormatados);
    }
    
    public function getAll()
    {
        $agendamentos = Agendamento::all();

        $agendamentosFormatted = $agendamentos->map(function ($agendamento) {
            return [
                'id' => $agendamento->id,
                'usuario_id' => $agendamento->usuario_id,
                'hora_inicio' => $agendamento->hora_inicio,
                'hora_fim' => $agendamento->hora_fim,
                'data' => $agendamento->data->format('Y-m-d'),
                'avaliacao' => $agendamento->avaliacao,
                'dia' => $agendamento->data->format('d'),
                'mes' => $agendamento->data->format('m'),
                'ano' => $agendamento->data->format('Y'),
                'confirmado' => $agendamento->confirmado,
            ];
        });

        return response()->json($agendamentosFormatted);
    }
    public function filterByDate(Request $request)
    {
        $data = $request->query('data');

        try {
            $carbonDate = Carbon::parse($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data inválida.'], 400);
        }

        $agendamentos = Agendamento::whereDate('data', $carbonDate)->get();

        $agendamentosFormatted = $agendamentos->map(function ($agendamento) use ($carbonDate) {
            return [
                'id' => $agendamento->id,
                'usuario_id' => $agendamento->usuario_id,
                'hora_inicio' => $agendamento->hora_inicio,
                'hora_fim' => $agendamento->hora_fim,
                'data' => $carbonDate->format('Y-m-d'),
                'avaliacao' => $agendamento->avaliacao,
                'dia' => $carbonDate->format('d'),
                'mes' => $carbonDate->format('m'),
                'ano' => $carbonDate->format('Y'),
                'confirmado' => $agendamento->confirmado,
            ];
        });

        return response()->json($agendamentosFormatted);
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|uuid',
            'hora_inicio' => 'required|string|size:2',
            'hora_fim' => 'required|string|size:2',
            'data' => 'required|date',
            'avaliacao' => 'nullable|integer',
            'confirmado' => 'nullable|boolean',
        ]);

        $agendamento = Agendamento::create($request->all());

        return response()->json($agendamento, 201);
    }

    public function update(Request $request, $id)
    {
        $agendamento = Agendamento::find($id);
    
        if (!$agendamento) {
            return response()->json(['message' => 'Agendamento não encontrado'], 404);
        }
    
        $request->validate([
            'hora_inicio' => 'nullable|string',
            'hora_fim' => 'nullable|string',
            'data' => 'nullable|date',
            'avaliacao' => 'nullable|integer',
            'usuario_id' => 'nullable|string',
            'confirmado' => 'nullable|boolean',
        ]);
    
        $agendamento->update($request->only([
            'hora_inicio', 'hora_fim', 'data', 'avaliacao', 'usuario_id','confirmado'
        ]));
    
        return response()->json($agendamento);
    }
    
    public function destroy($id)
    {
        $agendamento = Agendamento::find($id);

        if (!$agendamento) {
            return response()->json(['message' => 'Agendamento não encontrado'], 404);
        }

        $agendamento->delete();

        return response()->json(['message' => 'Agendamento deletado com sucesso']);
    }
}
